<?php

namespace App\Http\Controllers\DistributeEc;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Illuminate\Http\Request;
use App\Models\ResDistributionConfig;
use Illuminate\Support\Facades\DB;

class DistributeDataController extends Controller
{
    public function distribute_ec_data(Request $request){

        //第一步，取出需要自动分配的配置记录
        $distribution_arr = ResDistributionConfig::where('status','=','1')->where('auto_distribute_status','=','1')
            ->where('recyclable','=','1')->get();
        //自动分配表中，分配启用，并自动分配，判断是否在营业时间内
        $available_distribute_arr = [];
        if (empty($distribution_arr)){
            logger('自动下发资源可用配置为空');
            return '自动下发资源可用配置为空';
        }
        foreach ($distribution_arr as $key=>$single_distribution){
            if (empty($single_distribution->enable_time) || empty($single_distribution->disbale_time)){
                //营业时间未设置，直接跳出
                logger('营业时间未设置'.$single_distribution->id);
                continue;
            }else{
                //营业时间已设置
                $current_timestamp = time();//当前的时间戳
                $enable_timestamp = strtotime(date('Y-m-d ').$single_distribution->enable_time);
                $disable_timestamp = strtotime(date('Y-m-d ').$single_distribution->disbale_time);
                //判断当前时间是否还在营业时间内,(营业开始时间和结束时间 进行对比)
                if ($current_timestamp > $enable_timestamp && $current_timestamp < $disable_timestamp){
                    //在营业时间区间内
                    $available_distribute_arr[] = $single_distribution;
                }else{
                    //不在营业时间内
                    logger('不在营业时间内'.$single_distribution->id);
                    continue;
                }
            }
        }
        //已拿到营业时间的有效分配列表,接下来，拿到相应的下发资源，根据资源数来拿分配人员列表，进行组装请求ec （条件判定：所属、是否可以循环、排除列表）
//        dump($available_distribute_arr);
//        die();
        //接下来先判断所属和是否可循环，开始一条一条去判断
        if (empty($available_distribute_arr)){//可用循环为空，提前结束本次任务
            logger('可用循环为空，提前结束本次任务');
            return '可用循环为空，提前结束本次任务';
        }
        foreach ($available_distribute_arr as $single_available_distribute){
            //根据分配配置的所属查询需要分配资源列表
            $distribute_res_data_arr = $this->get_distribute_res_data($single_available_distribute);
            //根据资源的数量来确定需要多少人员
            if (empty($distribute_res_data_arr)){
                continue;
            }
            //需要下发数据不为空，去取相应的EC人员列表（包含老自动分配列表的数据的一个新数组）
            $new_auto_EcUser_list = array_values($this->get_distribute_EcUser_data($single_available_distribute,count($distribute_res_data_arr)));
            //接下来循环资源数组，拼接
            $list = [];
            $relate_customer_resData = [];//记录资源
            foreach ($distribute_res_data_arr as $key=>$single_res_data){
                //请求接口返回组装好的数据
                $userId = $new_auto_EcUser_list[$key];
                $list[$key] = $this->assembly_Ec_add_customer_data($single_res_data,$userId);
                $relate_customer_resData[$key] = ['res_data_id'=>$single_res_data->id,'ec_userId'=>$userId];
            }
            //customer 列表已加载完毕
            $optUserId = env('AUTO_DISTRIBUTE_OPTUSERID');
            echo $this->request_EC_add_customer($optUserId,$list,$relate_customer_resData,$new_auto_EcUser_list,$single_available_distribute->id);
//            die();
        }
    }

    //将获得数据，请求到Ec等待返回和操作
    public function request_EC_add_customer($optUserId,$list,$relate_customer_resData,$new_auto_EcUser_list,$res_distribute_config_id){
        logger('已准备好展示同步数据$res_distribute_config_id'.$res_distribute_config_id.'$optUserId'.$optUserId);
        logger('$list'.json_encode($list));
        logger('$relate_customer_resData'.json_encode($relate_customer_resData));
        logger('$new_auto_EcUser_list'.json_encode($new_auto_EcUser_list));
//        die();
        logger('到这为止，已经处理完毕等待放进ec系统中；');
        $url = env('EC_ADDCUSTOMER');
        $cid = env('EC_CID');
        $appId = env('EC_APPID');
        $appSecret = env('EC_APPSECRET');
        $psot_data = ['optUserId'=>$optUserId,'list'=>$list];
        $res_data_json = $this->http_get($url, $cid, $appId, $appSecret,'POST',$psot_data);
        $res_data = json_decode($res_data_json,true);
        logger('request_EC_add_customer2分配厚的数据为：',$res_data);
        //下面进行数据的记录
        //根据返回值进行判断
        if ($res_data['code'] == '200'){
            $success_EcUser_list = [];
            $failure_EcUser_list = [];
            //请求成功，判断成功和失败列表 进行更新
            if (!empty($res_data['data']['successIdList'])){
                foreach ($res_data['data']['successIdList'] as $success_data){ //{"index": 0,"crmId": 4262563847}
                    DB::table('res_data')->where('id', $relate_customer_resData['res_data_id'])
                        ->update(['crmId' => $success_data['crmId'] //数据库设计为字符串即可
                            ,'ec_userId' => $relate_customer_resData['ec_userId']
                            ,'failureCause' => ''
                            ,'synchronize_para' => $list[$success_data['index']] //相对应的用户
                            ,'synchronize_results'=>1
                        ]);
                    $success_EcUser_list[] = $relate_customer_resData['ec_userId'];
                    //操作完毕后，进行调用日志方法
                    $distribution_log_data = ['ec_userId' => $relate_customer_resData['ec_userId']
                        ,'failureCause' => ''
                        ,'synchronize_para' => $list[$success_data['index']] //相对应的用户
                        ,'synchronize_results'=>1];

                    $this->record_distribution_log($distribution_log_data,'1');

                }
            }
            if (!empty($res_data['data']['failureList'])){
                foreach ($res_data['data']['failureList'] as $failure_data){ //{"failureCause": "该客户被多人频繁操作，请稍后重试。", "index": 1}

                    DB::table('res_data')->where('id', $relate_customer_resData['res_data_id'])
                        ->update(['crmId' => '' //数据库设计为字符串即可
                            ,'ec_userId' => $relate_customer_resData['ec_userId']
                            ,'failureCause' => $failure_data['failureCause']
                            ,'synchronize_para' => $list[$failure_data['index']] //相对应的用户
                            ,'synchronize_results'=>0
                        ]);
                    //操作完毕后，进行调用日志方法
                    $distribution_log_data = ['ec_userId' => $relate_customer_resData['ec_userId']
                        ,'failureCause' => $failure_data['failureCause']
                        ,'synchronize_para' => $list[$failure_data['index']] //相对应的用户
                        ,'synchronize_results'=>0];
                    $this->record_distribution_log($distribution_log_data,'1');
                }
            }
            logger('自动分配完毕，如有异常已记录在数据中，请查看');
            //数据已分配完毕接下来是日志和更新 auto_distribute_list 字段
            //更新自动分配列表字段  $success_EcUser_list //已成功拿到资源的人员列表，从分配列表中去除，并更新
            if (empty($success_EcUser_list)){
                return '自动分配:分配成功列表为空';
            }else{
                //成功的列表不为空
                foreach ($success_EcUser_list as $single_success_EcUser){
                    $unset_key = array_search($single_success_EcUser,$new_auto_EcUser_list);
                    unset($new_auto_EcUser_list[$unset_key]);
                }
                DB::table('res_distribution_config')
                    ->where('id', '=',$res_distribute_config_id)
                    ->update(['auto_distribute_list' => json_encode($new_auto_EcUser_list)]);
            }
            return '已自动分配完毕';
        }else{
            //请求失败,不做操作 //code值非200
            logger('自动分配EC异常：'.$res_data['code'].':'.$res_data['msg']);
            return '自动分配:EC异常';
        }

    }

    public function record_distribution_log($distribution_log_data,$is_auto=0){
        date_default_timezone_set('Asia/Shanghai');
        logger('开始记录log日志(自动分配);日志数据：',$distribution_log_data);
        $synchronize_para = json_encode($distribution_log_data['synchronize_para']);
        $distribution_log_data['synchronize_para'] = $synchronize_para;
        $distribution_log_data['created_at'] = date('Y-m-d H:i:s');

        if ($is_auto == '1'){
            //是自动分配下发的
            $distribution_log_data['is_auto'] = '1';
        }else{
            $distribution_log_data['is_auto'] = '0';
        }
        DB::table('res_distribution_log')->insert($distribution_log_data);
        return '';
    }
    //获取多于资源数量的EC用户数
    public function get_distribute_EcUser_data($distribute_data,$res_data_sum){
        //先判断 auto_distribute_list 里面的数据是否够用，在判断是否需要加载进来
        $auto_distribute_access_arr = [];//组合后正常的数组
        //去除自动分配列表中的排除人员列表 (所属 循环列表 排除列表)
        $after_except_distribute_users = $this->get_after_except_arr($distribute_data);//获得了可直接分配的ec用户列表
        //接下来判断EC用户数量是否足够匹配 资源数量
        $access_distribute_users = $this->get_auto_distribute_arr($distribute_data,$res_data_sum,$after_except_distribute_users);//数量充足，状态有效，保留了原先的分配列表
        //从其中拿到对应数量的EC用户数
        return $access_distribute_users;

    }

    //拼接新增客户的数据列表
    public function assembly_Ec_add_customer_data($model,$userId){
        //查询当前列的数据
        $name = empty($model->data_name)?'未知':$model->data_name;
        if (empty($model->data_phone)){
            return [];
        }else{
            $phone = $model->data_phone;
        }
        //添加备注
        if ($model->type == '快马'){
            $data_arr = json_decode($model->data_json,true);
            $fastHorse_id =  $data_arr['id'];
            $fast_horse_message = $data_arr['message'];

        }elseif ($model->type == '全球'){
            $data_arr = json_decode($model->data_json,true);
            $fastHorse_id = '';
            $fast_horse_message = array_key_exists('original_brand',$data_arr)?$data_arr['original_brand']:'';

        }elseif ($model->type == '头条'){
            $data_arr = json_decode($model->data_json,true);
            $fastHorse_id = $data_arr['app_name'];
            $fast_horse_message = '';
        }elseif ($model->type == '5988'){
            $data_arr = json_decode($model->data_json,true);
            $fastHorse_id = '';
            $fast_horse_message = $data_arr['remark'];
        }elseif ($model->type == 'mail'){

            if (empty($model->data_json)){
                $fastHorse_id = '';
                $fast_horse_message = '';
            }else{
                $data_arr = json_decode($model->data_json,true);
                $fastHorse_id = '';
                $fast_horse_message = $data_arr['remarks'];
            }

        }else{
            $fastHorse_id = '';
            $fast_horse_message = '';
        }

        $memo = $model->belong.'-'.$model->type.'-'.$fastHorse_id.'-'.$fast_horse_message;
        return ['followUserId'=>$userId,'name'=>$name,'mobile'=>trim($phone),'memo'=>$memo];
    }

    //获取足够的EC用户数
    public function get_auto_distribute_arr($distribute_data,$res_data_sum,$access_distribute_users){ //用来合并和临时加载分配列表的地方

        if (is_array($distribute_data->recyclable_list)){
            $recyclable_arr =$distribute_data->recyclable_list;//总可分配列表
        }else{
            if (!isset($distribute_data->recyclable_list)){
                $recyclable_arr = [];
            }else{
                $recyclable_arr = json_decode($distribute_data->recyclable_list,true);
            }
        }

        if (empty($access_distribute_users)){
            //可分配的ec用户列表为空,加载可循环列表
            $access_distribute_users = array_keys($recyclable_arr);

        } elseif (count($access_distribute_users) >=  $res_data_sum){
            //可用的ec用户列表足够用来分配，不用加载 可循环列表

        }else{
            //可用的ec用户列表不足 用来分配,其他情况，加载 可循环列表
            $access_distribute_users = array_merge_recursive($access_distribute_users,array_keys($recyclable_arr));
        }
        //以获取加载过资源，判断是否足够
        //还需排除已经离职的人员
        $resign_users = DB::table('ec_users')->where('status','=','0')->get();
        foreach ($resign_users as $val){//排除了请假列表的数据
            if (in_array($val->userId,$access_distribute_users)){
                //需要排除,跳过
                $key = array_search($val->userId,$access_distribute_users);
                unset($access_distribute_users[$key]);
                continue;
            }else{
                //不需要排除
                continue;
            }
        }
        if (count($access_distribute_users) >= $res_data_sum){
            //数量足够，直接返回，先把排除列表中的数据，清除
            return $this->get_except_ec_arr($distribute_data,$access_distribute_users);
        }else{
            //数量还是不够，递归函数搞一下
            logger('递归函数搞了一下，记录下');
            return $this->get_auto_distribute_arr($distribute_data,$res_data_sum,$access_distribute_users);
        }

    }

    //清除EC用户列表中的排除数据
    public function get_except_ec_arr($distribute_data,$access_distribute_users){

        if (is_array($distribute_data->except_list)){
            $except_arr = $distribute_data->except_list;
        }else{
            if (!isset($distribute_data->except_list)){
                $except_arr = [];
            }else{
                $except_arr = json_decode($distribute_data->except_list,true);
            }

        }

        //还需排除已经离职的人员
        $leaved_ec_user = [];
        $resign_users = DB::table('users')->get();
        foreach ($resign_users as $val){//排除了请假列表的数据
            $leaved_ec_user[] = $val->userId;
        }

        $access_ec_user = [];
        foreach ($access_distribute_users as $key=>$distribute_user){
            if (in_array($distribute_user,$except_arr) || in_array($distribute_user,$leaved_ec_user)){
                //需要排除,跳过
                continue;
            }else{
                //不需要排除
                $access_ec_user[] = $distribute_user;
            }
        }
        return $access_ec_user;//这里返回的都是可以分配资源的EC人员
    }

    //得到排除之后的源EC用户列表
    public function get_after_except_arr($distribute_data){
        $auto_distribute_arr = json_decode($distribute_data->auto_distribute_list,true);//原自动下发的EC用户列表
        if (is_array($distribute_data->except_list)){
            $except_arr = $distribute_data->except_list;
        }else{
            if (!isset($distribute_data->except_list)){
                $except_arr = [];
            }else{
                $except_arr = json_decode($distribute_data->except_list,true);
            }

        }
        //还需排除已经离职的人员
        $leaved_ec_user = [];
        $resign_users = DB::table('ec_users')->where('status','=','0')->get();
        foreach ($resign_users as $val){//排除了请假列表的数据
            $leaved_ec_user[] = $val->userId;
        }

        $access_ec_user = [];
        if (empty($auto_distribute_arr)){
            return $access_ec_user;//源列表已经为空了
        }
        foreach ($auto_distribute_arr as $key=>$distribute_user){
            if (in_array($distribute_user,$except_arr) || in_array($distribute_user,$leaved_ec_user)){
                //需要排除,跳过
                continue;
            }else{
                //不需要排除
                $access_ec_user[] = $distribute_user;
            }
        }
        return $access_ec_user;//这里返回的都是可以分配资源的EC人员
    }

    //需要分配的相关资源的资源列表
    public function get_distribute_res_data($distribute_data){
        //查询还未分配的相关资源进行分配
        //取数据的条件：①前一天的结束营业时间，到当前时间 ②状态未分配 ③所属
        $start_date = date("Y-m-d",strtotime("-1 day")).' '.$distribute_data->disbale_time;
        $end_date = date('Y-m-d H:i:s');
//        DB::connection()->enableQueryLog();  // 开启QueryLog
        $distribute_rea_data_arr = ResData::where('synchronize_results','0')->whereNull('failureCause')->whereNull('ec_userId')
            ->where('belong','=',trim($distribute_data->belong))
            ->whereBetween('created_at', [$start_date, $end_date]) //资源的创建时间应该大于上一天的解释时间，和当前时间
            ->limit(49)
            ->orderBy('id', 'asc')
            ->get();
//        dump(DB::getQueryLog());select * from `res_data` where `synchronize_results` = 0 and `failureCause` is null and `ec_userId` is null and `belong` = '半城外' and `created_at` between '2021-03-12 18:00:00' and '2021-03-13 17:51:38'
        return $distribute_rea_data_arr;
    }


    /**
     *  签名算法
     *  该方法可以公用
     * @param int $timeStamp
     * @param string $appId
     * @param string $appSecret
     * @return string 返回签名数据
     */
    function getSign($timeStamp, $appId, $appSecret)
    {
        $sign = "appId={$appId}&appSecret={$appSecret}&timeStamp={$timeStamp}";
        return strtoupper(md5($sign));
    }
    /**
     * 该方法可以公用
     * 将参数拼接到url地址中
     * @param string $url
     * @param string $params
     * @return string|string|string
     */
    function addParmasToUrl($url, $params)
    {
        $urlParmas = $url;
        if (empty($params)) {
            return $urlParmas;
        }
        $isFist = true;
        foreach ($params as $key => $val) {
            if ($isFist) {
                $urlParmas = $urlParmas . "?" . $key . "=" . $val;
                $isFist = false;
            } else {
                $urlParmas = $urlParmas . "&" . $key . "=" . $val;
            }
        }
        return $urlParmas;
    }


}
