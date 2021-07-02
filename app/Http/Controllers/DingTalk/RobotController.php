<?php

namespace App\Http\Controllers\DingTalk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RobotController extends Controller
{
    //
    public function receive_robot_message(Request $request){
        $timestamp = $request->header('timestamp');
        $sign = $request->header('sign');
        $current_time = time();//当前时间戳
        $dingTalk_time_arr = explode('.',$timestamp*0.001);
        $dingTalk_time = $dingTalk_time_arr[0];
        //判断时间是否为非法请求
        if (abs($current_time-$dingTalk_time) >= 60*60){//时间差大于等于1小时，认定为非法请求
            logger('timestamp非法请求');
            return 'timestamp非法请求';
        }
        //判断sign值
        $robot_leave_appSecret = env('Robot_leave_appSecret');
        $sign_compute_str = $timestamp."\n".$robot_leave_appSecret;
        $own_sign = base64_encode(hash_hmac('sha256', $sign_compute_str, $robot_leave_appSecret, true));
        //判断sign是否为非法请求
        if ($own_sign != $sign){//判定sign值是否相等
            logger('sign非法请求');
            return 'sign非法请求';
        }
        //到这里已完成验证，接下来获取数据，开始处理
        logger('已验证完毕,进行数据的解析');
        $request_data = $request->all();
        logger(json_encode($request_data));
        //判断是否有钉钉用户id
        if (array_key_exists('senderStaffId',$request_data)){
            //钉钉用户id存在，可以调用操作
            return $this->opera_leave_data($request_data['senderStaffId'],trim($request_data['text']['content']));
        }else{
            //机器人未发布
            logger('机器人应用未发布');
            return '机器人未发布';
        }
    }

    //操作请假人员的状态
    public function opera_leave_data($senderStaffId,$content){
        //首先判断是否已经钉钉与EC相互绑定
        //首先使用钉钉的userid去查询是否已经有记录
        $dingTalk_ec_relative_data = DB::table('dingtalk_ec_relative')->where('dingtalk_userid','=',$senderStaffId)->first();
        if (empty($dingTalk_ec_relative_data)){
            //绑定关系为空，则未绑定，跳出
            return '还未绑定EC账号';
        }else{
            //已绑定成功的数据
            if ($content == '请假'){
                //用户请假，标记为请假状态，添加到排除列表中
                logger('请假');
            }elseif ($content == '上班'){
                logger('上班');
            }elseif ($content == '查询'){
                logger('查询');
            }else{
                //未知关键词，跳过
                logger('未知');
            }
            return '已绑定成功的数据，匹配关键词';
        }
    }

}
