<?php

namespace App\Admin\Actions\Res;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Distribution extends RowAction
{
    public $name = '分配';

    public function handle(Model $model,Request $request)
    {
        // $model ...
        // 获取到表单中的`ec_user`值
        $ec_user = $request->get('ec_user');
        if (empty($ec_user) || count($ec_user) != '1'){
            return $this->response()->error('分配人员异常，检查数量')->refresh();
        }
        $userId = $ec_user['0'];//用户ID
        logger('在分配数据给：'.$userId);

        //查询当前列的数据
        $name = empty($model->data_name)?'未知':$model->data_name;
        if (empty($model->data_phone)){
            return $this->response()->error('手机号为空')->refresh();
        }else{
            $phone = $model->data_phone;
        }
        //添加备注
        if ($model->type == '快马'){
            $data_arr = json_decode($model->data_json,true);
            $fastHorse_id =  $data_arr['id'];
            $fast_horse_message = $data_arr['message'];
        }if ($model->type == '头条'){
            $data_arr = json_decode($model->data_json,true);
            $fastHorse_id = $data_arr['app_name'];
            $fast_horse_message = '';
        }else{
            $fastHorse_id = '';
            $fast_horse_message = '';
        }

        $memo = $model->belong.'-'.$model->type.'-'.$fastHorse_id.'-'.$fast_horse_message;
        $customer[] = ['followUserId'=>$userId,'name'=>$name,'mobile'=>$phone,'memo'=>$memo];
        $url = env('EC_ADDCUSTOMER');
        $cid = env('EC_CID');
        $appId = env('EC_APPID');
        $appSecret = env('EC_APPSECRET');
        $psot_data = ['optUserId'=>$userId,'list'=>$customer];
        $res_data_json = $this->http_get($url, $cid, $appId, $appSecret,'POST',$psot_data);
        $res_data = json_decode($res_data_json,true);
        logger('分配厚的数据为：',$res_data);

        //下面进行数据的记录
        //根据返回值进行判断
        if ($res_data['code'] == '200'){
            //请求成功，判断成功和失败列表 进行更新
            if (!empty($res_data['data']['successIdList'])){
                foreach ($res_data['data']['successIdList'] as $success_data){ //{"index": 0,"crmId": 4262563847}

                    DB::table('res_data')->where('id', $model->id)
                        ->update(['crmId' => $success_data['crmId'] //数据库设计为字符串即可
                            ,'ec_userId' => $userId
                            ,'failureCause' => ''
                            ,'synchronize_para' => $customer[$success_data['index']] //相对应的用户
                            ,'synchronize_results'=>1
                        ]);
                }
            }
            if (!empty($res_data['data']['failureList'])){
                foreach ($res_data['data']['failureList'] as $failure_data){ //{"failureCause": "该客户被多人频繁操作，请稍后重试。", "index": 1}

                    DB::table('res_data')->where('id', $model->id)
                        ->update(['crmId' => '' //数据库设计为字符串即可
                            ,'ec_userId' => $userId
                            ,'failureCause' => $failure_data['failureCause']
                            ,'synchronize_para' => $customer[$failure_data['index']] //相对应的用户
                            ,'synchronize_results'=>0
                        ]);
                }
            }
            logger('分配完毕，如有异常已记录在数据中，请查看');


        }else{
            //请求失败,不做操作
            logger('EC异常：'.$res_data['code'].':'.$res_data['msg']);
            return $this->response()->error('EC异常：'.$res_data['code'].':'.$res_data['msg'])->refresh();
        }

        return $this->response()->success('分配成功')->refresh();
    }

    public function form()
    {
        $users = DB::table('ec_users')->where('status','=','1')->get()->toArray();
        $depts = DB::table('ec_depts')->get()->toArray();
        $complete_depts = [];
        foreach ($depts as $dept){
            $complete_depts[$dept->deptId] = ['deptName'=>$dept->deptName,'parentDeptId'=>$dept->parentDeptId];
        }

        $finish_user_arr = [];
        foreach ($users as $user){
            //判断deptId是否为0
            if ($user->deptId == '0'){
                //部门为0，直接当前记录的部门值赋值
                $finish_user_arr[$user->userId] = $user->title.'-'.$user->userName;
            }else{
                //部门ID不为0，调用接口去循环部门
                $dept_PreName_arr = $this->get_user_depts([],$user->deptId,$complete_depts);
                $finish_user_arr[$user->userId] = implode('-',array_reverse($dept_PreName_arr)).'-'.$user->title.'-'.$user->userName;
            }
        }
//        var_dump($finish_user_arr);//10575740
//        die();
//        $this->multipleSelect('ec_user', '招商')->options($finish_user_arr)->rules('required')->default(['10575740']);
        $this->multipleSelect('ec_user', '招商')->options($finish_user_arr)->rules('required');
    }

    public function get_user_depts($res,$deptId,$depts){
        //第一步直接根据部门ID取部门值
        $res[] = $depts[$deptId]['deptName'];

        //判断对否还有父部门
        if ($depts[$deptId]['parentDeptId'] == '0'){
            return $res;
        }else{
            return $this->get_user_depts($res,$depts[$deptId]['parentDeptId'],$depts);
        }

    }




//EC的封装方法

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
     * get 请求业务
     * 该方法可以公用
     *
     * @param string $url
     * @param string $jsonData
     * @param int $cid
     * @param string $appId
     * @param string $appSecret
     */
    function http_get($url, $cid, $appId, $appSecret,$method = 'GET',$psot_data =[])
    {
        // 1. 获取当前时间戳
        $timeStamp = time() * 1000;
        // 2. 获取签名
        $sign = $this->getSign($timeStamp, $appId, $appSecret);
        // 3. 封装请求头
        $head = array(
            'Content-Type: application/json; charset=utf-8',
            'X-Ec-Cid: ' . $cid,
            'X-Ec-Sign: ' . $sign,
            'X-Ec-TimeStamp: ' . $timeStamp
        );
        // 3. 传入http 参数
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // https 支持 - 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // head
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);

        if($method == 'POST'){
            //设置post方式提交
            curl_setopt($ch, CURLOPT_POST, 1);
            //设置post数据
            $post_data = json_encode($psot_data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }


        // 请求服务器
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // 组织，返回结果和响应码
        return $response;
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
