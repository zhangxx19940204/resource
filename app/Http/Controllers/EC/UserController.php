<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    //
    public function get_framework_info(Request $request){
        $url = env('EC_STRUCT_INFO');
        $cid = env('EC_CID');
        $appId = env('EC_APPID');
        $appSecret = env('EC_APPSECRET');
        $res_data_json = $this->http_get($url, $cid, $appId, $appSecret);
        $res_data = json_decode($res_data_json,true);
//        echo '<pre>';
//        var_dump(json_encode($res_data));
//        echo '</pre>';
//        die();
        DB::beginTransaction();
        try {
//            DB::table('ec_users')->truncate();
//            DB::table('ec_users')->insert($res_data['data']['users']);
//            DB::table('ec_depts')->truncate();
//            DB::table('ec_depts')->insert($res_data['data']['depts']);
            DB::table('ec_users')->update(['status' => 0]);
            foreach ($res_data['data']['users'] as $user){
                DB::table('ec_users')
                    ->updateOrInsert(
                        ['userId' => $user['userId']],
                        ['userName' => $user['userName'],'title' => $user['title'],'status' => $user['status'],'deptId' => $user['deptId']]
                    );
            }

            foreach ($res_data['data']['depts'] as $dept){
                DB::table('ec_depts')
                    ->updateOrInsert(
                        ['deptId' => $dept['deptId']],
                        ['deptName' => $dept['deptName'],'parentDeptId' => $dept['parentDeptId']]
                    );
            }

            DB::commit();
            logger('数据库更新或者操作成功');

        } catch (Exception $e ) {
            logger('数据库更新或者操作失败');
            DB::rollBack();
        }


    }

    public function synchronous_single_feedback(Request $request){

        $para = $request->all();
        //判断crmId是否存在系统中，根据crmId查询是否存在，是否需要同步反馈
        $data = DB::table('res_data')->where('crmId','=',$para['crmId'])->first();
        if (empty($data)){
            return json_encode(['code'=>202,'msg'=>'请前往钉钉反馈']);
        }
//        var_dump($data);
//        die();
        //判断是否需要同步跟进记录
        if ($data->feedback_status == '1'){
            //已反馈
            return json_encode(['code'=>202,'msg'=>'此记录已反馈完毕']);
        }else{
            //还未反馈
            logger('EC用户点击同步按钮获得的参数：');
            logger($para);//array ('crmId' => 4264496813, 'crmMobile' => '15153619259', 'optUserId' => 11153702)
            $single_feedback_data = $this->get_ec_customer_last_feedback($para);
            logger('EC返回的跟进数据：');
            logger(json_encode($single_feedback_data));
            //获取到了数据，进行反馈数据的存储
            //判断EC返回值的状态
            if ($single_feedback_data['code'] == '200'){
                //EC返回值正常
                $feedback_arr = [];
                if (empty($single_feedback_data['data']['trajectoryList'])){
                    return json_encode(['code'=>202,'msg'=>'跟进记录为空']);
                }
                foreach ($single_feedback_data['data']['trajectoryList'] as $feedback_data){//循环单个账号下的跟进记录
                    $create_time = strtotime($feedback_data['createTime']);
                    $feedback_arr[$create_time] = $feedback_data;
                }

                DB::beginTransaction();
                try {
                    $update_id = DB::table('res_data')->where('id', $data->id)->update(['feedback_status' => 1,'feedback_content'=>$feedback_arr]);
                    logger('反馈更新成功'.$update_id);
                    return json_encode(['code'=>200,'msg'=>'同步成功','data'=>['text'=>'同步成功']]);

                } catch (Exception $e ) {
                    logger($data->id.'反馈更新失败:'.$e->getMessage());
                    DB::rollBack();
                    return json_encode(['code'=>202,'msg'=>'同步失败']);
                }




            }else{
                //EC返回值异常
                logger('EC返回值异常：'.$single_feedback_data['code'].';提示信息：'.$single_feedback_data['msg']);
                return json_encode(['code'=>202,'msg'=>'系统异常，稍后再试一次']);
            }

        }
    }

    public function get_ec_customer_last_feedback($single_data){
        date_default_timezone_set('Asia/Shanghai');
        //调用接口获取最新的一条反馈
        $url = env('EC_GETTRAJECTORY');
        $cid = env('EC_CID');
        $appId = env('EC_APPID');
        $appSecret = env('EC_APPSECRET');
        $post_data = [
            'crmIds'=>$single_data['crmId'],
            'date'=>['startTime'=>date('Y-m-d').' 00:00:00','endTime'=>date('Y-m-d',strtotime('+1 day')).' 00:00:00'],
            'trajectoryType'=>4000
            ];
        logger('记录请求EC的参数：');
        logger($post_data);
        $res_data_json = $this->http_get($url, $cid, $appId, $appSecret,'POST',$post_data);
        return json_decode($res_data_json,true);
    }

    public function add_deptName(){
        $ec_users_obj = DB::table('ec_users')->get();
        $depts = DB::table('ec_depts')->get()->toArray();
        $complete_depts = [];
        foreach ($depts as $dept){
            $complete_depts[$dept->deptId] = ['deptName'=>$dept->deptName,'parentDeptId'=>$dept->parentDeptId];
        }

        foreach ($ec_users_obj as $ec_user){
            $dept_PreName_arr = $this->get_user_depts([],$ec_user->deptId,$complete_depts);
            $user_dept_str = implode('-',array_reverse($dept_PreName_arr)).'-'.$ec_user->title.'-'.$ec_user->userName;
            DB::table('ec_users')->where('id', '=',$ec_user->id)->update(['deptName' => $user_dept_str]);
//            logger(';userId:'.$ec_user->id);
        }
        return '部门名称更新完毕，可以使用了';
    }

    public function get_user_depts($res,$deptId,$depts){
        //第一步直接根据部门ID取部门值
        if ($deptId == '0'){
            //第一不属于任何部门
            return ['德胜企业'];
        }
        $res[] = $depts[$deptId]['deptName'];
        //判断对否还有父部门
        if ($depts[$deptId]['parentDeptId'] == '0'){
            return $res;
        }else{
            return $this->get_user_depts($res,$depts[$deptId]['parentDeptId'],$depts);
        }

    }

    public function synchronous_failureCause_userId(){
        date_default_timezone_set('Asia/Shanghai');
        //调用接口获取最新的一条反馈
        $url = env('EC_QUERY');
        $cid = env('EC_CID');
        $appId = env('EC_APPID');
        $appSecret = env('EC_APPSECRET');
        logger('synchronous_failureCause_userId；');

        //查询当前手机重复的记录，准备一次去请求
        $synchronize_fail_list = DB::table('res_data')
            // ->whereNull('crmId')
            ->whereNull('exist_ec_userId')
            ->where('synchronize_results','=','0')
            ->where('failureCause','=','手机号已被其他客户使用')->get()->toArray();
        if (empty($synchronize_fail_list)){
            return '暂无新的占用手机号';
        }
        foreach ($synchronize_fail_list as $synchronize_fail_data){
            $post_data = [
                'mobile'=>$synchronize_fail_data->data_phone,
            ];
            logger('请求手机重复记录的客户记录请求EC的参数：');
            logger($post_data);
            $res_data_json = $this->http_get($url, $cid, $appId, $appSecret,'POST',$post_data);
            $res_data_arr = json_decode($res_data_json,true);
            logger(json_encode($res_data_arr));
            if (empty($res_data_arr['data']['customerInfoList'])){
                //公海资源，没有指定人
                continue;
            }else{
                //资源原先已有指定人
                DB::table('res_data')->where('id','=',$synchronize_fail_data->id)
                    ->update(['exist_ec_userId' =>$res_data_arr['data']['customerInfoList'][0]['followUserId'] ]);
            }

        }

    }

    public function add_feedbackContent_short(){
        //先去查询没有简短反馈的相关数据
        $add_shortWord_list = DB::table('res_data')
            ->where('feedback_status','=','1')
            ->whereNotNull('feedback_content')
            ->get()->toArray();
        $find_list =  DB::table('short_feedback_relative')->get()->toArray();
        $find_arr = [];
        foreach ($find_list as $single_find){
            $find_arr[$single_find->short_feeback] = json_decode($single_find->find_keywords_list,true);
        }
//        dump($find_arr);
//        die();
        foreach ($add_shortWord_list as $add_shortWord){ //这个循环的数据为：需要添加简短反馈的数据列表

            $content_arr = json_decode($add_shortWord->feedback_content,true);

            $content_str = array_shift($content_arr)['content'];//反馈的内容，根据这个内容进行判断
            //获取完毕反馈的内容，下面进行数据判断
            $short_feeback_str = $this->get_short_feedback_str($find_arr,$content_str);
            if (empty($short_feeback_str)){
                continue;
            }else{
                DB::table('res_data')->where('id', '=',$add_shortWord->id)->update(['short_feedback' => $short_feeback_str]);
                logger('简短反馈更新完毕:'.$add_shortWord->id);
            }

        }

    }

    public function get_short_feedback_str($find_arr,$content_str){
        $res_str = '';
        foreach ($find_arr as $short_feeback =>$single_find_arr){ //循环多个类别
            foreach ($single_find_arr as $single_word){ //这个循环的是一个类别下的多个关键字
                if(strpos($content_str,$single_word)!==false){
                    //匹配到关键字，直接返回，不在继续循环
                    $res_str = $short_feeback;
                    break;
                }else{
                    //此类中，此关键字，不匹配
                    continue;
                }
            }
        }

        return $res_str;

    }
}
