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
            logger('机器人应用已发布');
            $res_dingRobot = $this->opera_leave_data($request_data['senderStaffId'],trim($request_data['text']['content']));
            logger($res_dingRobot);
            return response()->json($res_dingRobot);
        }else{
            //机器人未发布
            logger('机器人应用未发布');
            $res_dingRobot = ['msgtype'=>"text","text"=>["content"=>'机器人应用未发布']];
            return response()->json($res_dingRobot);
        }
    }

    //操作请假人员的状态
    public function opera_leave_data($senderStaffId,$content){
        //首先判断是否已经钉钉与EC相互绑定
        //首先使用钉钉的userid去查询是否已经有记录
        $dingTalk_ec_relative_data = DB::table('dingtalk_ec_relative')->where('dingtalk_userid','=',$senderStaffId)->first();
        if (empty($dingTalk_ec_relative_data)){
            //绑定关系为空，则未绑定，跳出
            logger("还未绑定EC账号");
            //记录用户的关键词记录
            $res_text = "EC账号未绑定";
            $this->record_keyWords_response($senderStaffId,$content,$res_text);
            return ['msgtype'=>"text","text"=>["content"=>$res_text],"at"=>["atUserIds"=>[$senderStaffId],"isAtAll"=>false]];
        }else{
            //已绑定成功的数据
            if ($content == '停资源'){
                //用户请假，标记为请假状态，添加到排除列表中
                logger('请假');
                $res_text =$this->change_except_list('leave',$dingTalk_ec_relative_data->ec_userid);
                // return ['msgtype'=>"text","text"=>["content"=>$res_text],"at"=>["atUserIds"=>[$senderStaffId],"isAtAll"=>false]];

            }elseif ($content == '接资源'){
                logger('上班');
                $res_text = $this->change_except_list('work',$dingTalk_ec_relative_data->ec_userid);
                // return ['msgtype'=>"text","text"=>["content"=>$res_text],"at"=>["atUserIds"=>[$senderStaffId],"isAtAll"=>false]];

            }elseif ($content == '查询'){
                logger('查询');
                $res_text = "查询联系管理员";
                // return ['msgtype'=>"text","text"=>["content"=>"查询联系管理员"],"at"=>["atUserIds"=>[$senderStaffId],"isAtAll"=>false]];
            }else{
                //未知关键词，跳过
                logger('未知');
                $res_text = "未知";
                // return ['msgtype'=>"text","text"=>["content"=>"未知"],"at"=>["atUserIds"=>[$senderStaffId],"isAtAll"=>false]];
            }
            //记录用户的关键词记录
            $this->record_keyWords_response($senderStaffId,$content,$res_text);
            return ['msgtype'=>"text","text"=>["content"=>$res_text],"at"=>["atUserIds"=>[$senderStaffId],"isAtAll"=>false]];

        }
    }

    public function change_except_list($type,$ec_userid){
        //这里去添加和修改请假列表
        $res_distribution_config_list = DB::table('res_distribution_config')->get();
        $Robot_allow_keywords = env('Robot_allow_keywords');
        if ($Robot_allow_keywords == ''){
            //允许的字段为空，则不通过操作
            return '自主请假操作暂未开放';
        }
        $Robot_allow_keywords_arr = explode(',',$Robot_allow_keywords);
        //第一步先判断操作类型
        if ($type == 'leave'){
            if (!in_array('leave',$Robot_allow_keywords_arr)){
                //未允许此操作
                return '停资源：操作暂未开通';
            }
            //增加，添加ecuser到排除列表中
            //判断是否为null
            $new_except_arr = [];
            foreach ($res_distribution_config_list as $res_distribution_config){
                if (!is_null($res_distribution_config->except_list)){
                    //排除列表不为null，是个数组
                    $except_arr = json_decode($res_distribution_config->except_list,true);
                    $except_arr[] = $ec_userid;
                }else{
                    //排除列表为null
                    $except_arr = [$ec_userid];
                }
                $new_except_arr[] = ['id'=>$res_distribution_config->id,'new_except_arr'=>$except_arr];
            }
            foreach ($new_except_arr as $new_except){
                DB::table('res_distribution_config')->where('id', '=',$new_except['id'])
                    ->update(['except_list' => json_encode($new_except['new_except_arr'])]);
            }
            return '停止接资源';

        }elseif ($type == 'work'){
            if (!in_array('work',$Robot_allow_keywords_arr)){
                //未允许此操作
                return '接资源：操作暂未开通';
            }
            //从排除列表中移除
            //判断是否为null
            $new_except_arr = [];
            foreach ($res_distribution_config_list as $res_distribution_config){
                if (!is_null($res_distribution_config->except_list)){
                    //排除列表不为null，是个数组
                    $except_arr = json_decode($res_distribution_config->except_list,true);
                    //查找值，找到并删除
                    $search_key = array_search($ec_userid,$except_arr);
                    if(is_numeric($search_key)){//有值，直接删除
                        unset($except_arr[$search_key]);
                    }
                }else{
                    //排除列表为null
                    $except_arr = [];
                }
                $new_except_arr[] = ['id'=>$res_distribution_config->id,'new_except_arr'=>$except_arr];
            }
            foreach ($new_except_arr as $new_except){
                DB::table('res_distribution_config')->where('id', '=',$new_except['id'])
                    ->update(['except_list' => json_encode($new_except['new_except_arr'])]);
            }
            return '开始接资源';
        }else{
            //类型未知
            return 'change：$type；未知';
        }
    }

    public function record_keyWords_response($senderStaffId,$keyWord,$response){
        try {
            DB::table('record_leave_robot_data')->insert(
                ['dingding_userid' => $senderStaffId, 'key_word' => $keyWord, 'res_word'=>$response]
            );
            return '';
        } catch (\Exception $e) {
            logger("record_keyWords_response:".$e->getMessage());
            return '';
        }
    }



}
