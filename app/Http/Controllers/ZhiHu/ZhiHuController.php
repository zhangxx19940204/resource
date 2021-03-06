<?php

namespace App\Http\Controllers\ZhiHu;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ZhiHuController extends Controller
{
    //
    public function  get_zhihu_data(Request $request){
        date_default_timezone_set('Asia/Shanghai');
        logger('开始请求知乎信息流的数据：');
        //开始处理传来的数据
        $config_data = DB::table('res_config')->where('status','=','1')
            ->where('type','=','知乎信息流')->get();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无对应有效账号:');
            return ['success'=>'失败','msg'=>'获取的账号未设置或未启用','code'=>400,'response_code'=>0];
        }
        //查询到知乎信息流的账号，进行数据的整理和存储
        logger('知乎信息流的账号列表：'.$config_data);
        $current_datetime = date('Y-m-d H:i:s');
        foreach ($config_data as $config){
            //单独的全球的账号,进行请求判定
            $id = $config->account_id;
            $token = $config->account_password;
            $timestamp = time();
            $signature = $this->get_zhihu_signature($id,$token,$timestamp);
//            var_dump($timestamp,$signature);
            $para = [
                'from'=>date("Y-m-d",strtotime("-1 day")),
                'to'=>date("Y-m-d",strtotime("+1 day")),
                'userId'=>$id,
                'signStamp'=>$timestamp,
                'signature'=>$signature,
            ];
            $para_str_url = http_build_query($para);
            $quest_url = env('ZHIHU_DATA_URL').'?'.$para_str_url;
            $msg_json_str = file_get_contents($quest_url);
            $message_data_arr = json_decode($msg_json_str,true);//数组
            if ($message_data_arr['code'] != '200'){
                //判断status为false，记录日志
                logger('status为：'.$message_data_arr['code'].'；账号获取数据异常:'.$config->id.';报错为:'.$message_data_arr['msg']);
                continue;
            }
            //账号数据应该正常
            //先去查询最近的一条数据（原因为每次返回两个日期零点的数据，所以总有重复返回，要做判断）
            $last_data = ResData::where('config_id', $config->id)->orderBy('id', 'desc')->first();//数据的入库时间一定是上次最新的
            $add_data_arr = [];
            if (empty($last_data)){
                //第一次请求，全部的100条，记录进数据库
                if (empty($message_data_arr['data'])){
                    //传过来的数组数据为空
                    continue;
                }else {
                    //数据数组不为空
                    foreach ($message_data_arr['data'] as $single_message_data){
                        $add_data_arr[] = ['user_id' => $config->user_id, 'config_id' => $config->id
                            ,'created_at'=>$current_datetime,'updated_at'=>$current_datetime
                            ,'belong'=>$config->belong,'type'=>$config->type,'data_json'=>json_encode($single_message_data)
                            ,'data_name'=>(array_key_exists('userName',$single_message_data)?$single_message_data['userName']:'')
                            ,'data_phone'=>trim(substr(trim($single_message_data['phone']), -11))
                        ];
                    }
                }


            }else{
                //不是第一次请求，已正常拿到上一次入库的数据
                $last_msg_data_arr = json_decode($last_data->data_json,true);//上一次请求的最新的数据
                if (empty($message_data_arr['data'])){
                    //传过来的数组数据为空
                    continue;
                }else{
                    //数据数组不为空
                    foreach ($message_data_arr['data'] as $single_message_data){
                        //判断时间和data_json 中的id，共同确定
                        if ($last_msg_data_arr['phone'] == $single_message_data['phone']){
                            //已循环到上次循环的记录
                            break;
                        }
                        $add_data_arr[] = ['user_id' => $config->user_id, 'config_id' => $config->id
                            ,'created_at'=>$current_datetime,'updated_at'=>$current_datetime
                            ,'belong'=>$config->belong,'type'=>$config->type,'data_json'=>json_encode($single_message_data)
                            ,'data_name'=>(array_key_exists('userName',$single_message_data)?$single_message_data['userName']:'')
                            ,'data_phone'=>trim(substr(trim($single_message_data['phone']), -11))
                        ];
                    }
                }

            }

            //插入数据库
//            dump('插入数据库的数组：',$add_data_arr);
//            die();
            try {
                DB::beginTransaction();
                DB::table('res_data')->insert(array_reverse($add_data_arr));//数组的反转为了保持数据中的id大的在前，为了下次入库做准备
                DB::commit();
            }catch (Exception $e){
                DB::rollBack();
            }

            logger(date('Y-m-d H:i:s').'知乎信息流账号：'.$config->id.'正常');
        }

    }
    public function get_zhihu_signature($id,$token,$timestamp){
        //id token timestamp
        $str = $id.$token.$timestamp;
        return md5($str);
    }

}
