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
//        var_dump($res_data);
//        echo '</pre>';
//        die();
        DB::beginTransaction();
        try {
            DB::table('ec_users')->truncate();
            DB::table('ec_depts')->truncate();
            DB::table('ec_users')->insert($res_data['data']['users']);
            DB::table('ec_depts')->insert($res_data['data']['depts']);
            DB::commit();
            logger('数据库更新或者操作成功');

        } catch (Exception $e ) {
            logger('数据库更新或者操作失败');
            DB::rollBack();
        }


    }

    public function synchronous_single_feedback(Request $request){

        $para = $request->all();//array ('crmId' => 4264425363, 'crmMobile' => '15153619259', 'optUserId' => 11153702);//$request->all();
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
            logger($para);//array ('crmId' => 4264496813, 'crmMobile' => '15153619259', 'optUserId' => 11153702)
            $single_feedback_data = $this->get_ec_customer_last_feedback($para);
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
                    return json_encode(['code'=>200,'msg'=>'同步成功']);

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
//            'date'=>['startTime'=>'2020-11-27 00:00:00','endTime'=>'2020-11-28 00:00:00'],
            'trajectoryType'=>4000
            ];
        $res_data_json = $this->http_get($url, $cid, $appId, $appSecret,'POST',$post_data);
        return json_decode($res_data_json,true);
    }
}
