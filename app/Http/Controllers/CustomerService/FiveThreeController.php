<?php

namespace App\Http\Controllers\CustomerService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FiveThreeController extends Controller
{
    //接收53kf 的数据,在进行分支
    public function receive_53kf_info(Request $request){
//        logger('receive_53kf_message_info:'.json_encode($request->all()));
        $customer_data = $request->all(); //{"cmd":"talk_info","msg_id":"ba667079-9f31-4522-9569-0775c14a06fc","content":"这里是url_encode的字符串","token":"rH0D52587Wt54mFok"}
        //先判断是否是百度关键词推送
        if (!array_key_exists('cmd',$customer_data)){
            //此条记录没有cmd字段，可能是百度关键词的推送，先logger，不做操作
            logger('cmd不存在'.json_encode($customer_data));
            return ['cmd'=>'OK','token'=>''];
        }else{
            //在推送的数据中，可以查询到cmd字段，可能是 聊天数据或者客户数据
            //共有的字段为cmd，可以判断为哪一个接口推送的数据
            //客服系统数据对接有效，判断返回的类型
            if ($customer_data['cmd'] == 'talk_info'){
                //整体消息数据的推送
                $complete_data = json_decode(urldecode($customer_data['content']),true);
                //先去判断token是否在系统中
                $customerService_config = DB::table('customerservice_config')->where('status','1')->where('token',$customer_data['token'])->first();
                if (empty($customerService_config)){
                    //此推送不在系统配置中,直接返回就好
                    logger('系统未配置');
                    return ['cmd'=>'OK','token'=>$customer_data['token']];
                }
                $token = $this->receive_53kf_message_info($complete_data,$customerService_config);

            }elseif ($customer_data['cmd'] == 'customer'){
                //客户信息的数据的推送
                $complete_data = json_decode(urldecode($customer_data['content']),true);
                //先去判断token是否在系统中
//                $customerService_config = DB::table('customerservice_config')->where('status','1')->where('token',$customer_data['token'])->first();
//                if (empty($customerService_config)){
//                    //此推送不在系统配置中,直接返回就好
//                    logger('系统未配置');
//                    return ['cmd'=>'OK','token'=>$customer_data['token']];
//                }
//                $token = $this->receive_53kf_user_info($complete_data,$customerService_config);
                logger('记录下customer的'.json_encode($customer_data));
            }elseif($customer_data['cmd'] == 'activate'){
                //激活的推送
                return ['cmd'=>'OK','token'=>$customer_data['token']];
            }else{
                logger('else:cmd不存在'.json_encode($customer_data));
                return ['cmd'=>'OK','token'=>''];
            }

        }
        return ['cmd'=>'OK','token'=>$token];
    }
    //接收53客服的整体的消息数据
    public function receive_53kf_message_info($data,$customerService_config){//para 数组
        //判断session是否需要更新
        $data_session = $data['session'];
        $data_end = $data['end'];
        $origin_data = DB::table('customerservice_record')->where('config_id',$customerService_config->id)
            ->where('data_guest_id',$data['session']['guest_id'])->first();
        //组装message信息 $data['message'];
        if (!empty($origin_data)){
            $data_message = json_decode($origin_data->data_message,true);
        }else{
            $data_message = [];
        }
        foreach ($data['message'] as $single_message){
            $data_message[$single_message['talk_id']][] = $single_message;
        }

        //去判断其他的数据（电话和微信）是否存在和正常来进行判断，并存入数据库中
//        isMobile  isQQ

        if (empty($origin_data)){
            //数据为空，新增一条记录
            DB::table('customerservice_record')->insert(
                ['config_id' => $customerService_config->id,
                    'data_guest_id' =>$data['session']['guest_id'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'data_message'=>json_encode($data_message),
                    'data_end'=>json_encode($data_end),
                    'data_session'=>json_encode($data_session)
                ]
            );
        }else{
            //此数据已存在，进行更新操作
            DB::table('customerservice_record')
                ->where('id', $origin_data->id)
                ->update(['updated_at' => date('Y-m-d H:i:s'),
                    'data_message'=>json_encode($data_message),
                    'data_end'=>json_encode($data_end),
                    'data_session'=>json_encode($data_session)
                ]);
        }
        return $customerService_config->token;
    }

    //接收53客服的客户消息
    public function receive_53kf_user_info(){
        return '';
    }
}
