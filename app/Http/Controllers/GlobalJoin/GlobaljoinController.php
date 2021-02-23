<?php

namespace App\Http\Controllers\GlobalJoin;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class GlobaljoinController extends Controller
{
    //接收全球加盟网传送过来的数据
    public function get_global_join (Request $request){
        date_default_timezone_set('Asia/Shanghai');
        logger('开始请求全球加盟网的数据：');
        //开始处理传来的数据

        //第一步开始去查询相应的账号对应
        $config_data = DB::table('res_config')->where('status','=','1')->where('type','=','全球')->get();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无对应有效账号:');
            return ['success'=>'失败','msg'=>'获取的账号未设置或未启用','code'=>400,'response_code'=>0];
        }

        //查询到全球的账号，进行数据的整理和存储
        $api_url = 'http://zs.jiameng.com/service/msgOpenService.html';
        foreach ($config_data as $config){
            //单独的全球的账号,进行请求判定
            $para = [
                'login_name'=>$config->account,
                'transfer_key'=>$config->account_password,
                'transfer_offset'=>$config->account_id,
            ];
            $current_datetime = date('Y-m-d H:i:s');
            $msg_json_str = $this->simple_post($api_url,$para);
            $message_data_arr = json_decode($msg_json_str,true);//数组
//            dump($message_data_arr);
//            die();
            if ($message_data_arr['status'] == 'false'){
                //判断status为false，记录日志
                logger('status为：false；账号h获取数据异常:'.$config->id.';报错为:'.$msg_json_str);
                continue;
            }
            //账号数据应该正常
            //先去查询最近的一条数据（原因为每次返回最近的100条，所以要判断）
            $last_data = ResData::where('config_id', $config->id)->orderBy('id', 'desc')->first();//数据的入库时间一定是上次最新的
            $add_data_arr = [];
            if (empty($last_data)){
                //第一次请求，全部的100条，记录进数据库
                foreach ($message_data_arr['data'] as $single_message_data){
                    $add_data_arr[] = ['user_id' => $config->user_id, 'config_id' => $config->id
                        ,'created_at'=>$current_datetime,'updated_at'=>$current_datetime
                        ,'belong'=>$config->belong,'type'=>$config->type,'data_json'=>json_encode($single_message_data)
                        ,'data_name'=>(array_key_exists('name',$single_message_data)?$single_message_data['name']:'')
                        ,'data_phone'=>trim($single_message_data['phone'])];
                }

            }else{
                //不是第一次请求，已正常拿到上一次入库的数据
                $last_msg_data_arr = json_decode($last_data->data_json,true);//上一次请求的最新的数据
                foreach ($message_data_arr['data'] as $single_message_data){
                    //判断时间和data_json 中的id，共同确定

//                    if (trim($last_data->data_phone) == trim($single_message_data['phone']) && trim($last_data->data_name) == trim($single_message_data['name']) ){
//                        //资源的手机号和名称相同，判断data_json中的id是否相等
//
//                    }else{
//
//                    }
                    if ($last_msg_data_arr['id'] == $single_message_data['id']){
                        //已循环到上次循环的记录
                        break;
                    }

                    $add_data_arr[] = ['user_id' => $config->user_id, 'config_id' => $config->id
                        ,'created_at'=>$current_datetime,'updated_at'=>$current_datetime
                        ,'belong'=>$config->belong,'type'=>$config->type,'data_json'=>json_encode($single_message_data)
                        ,'data_name'=>(array_key_exists('name',$single_message_data)?$single_message_data['name']:'')
                        ,'data_phone'=>trim($single_message_data['phone'])];
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

            logger(date('Y-m-d H:i:s').'全球网账号：'.$config->id.'正常');
        }


    }
}
