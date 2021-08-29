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
        if (!array_key_exists('cmd',$customer_data)){   //**********************************
            //此条记录没有cmd字段，可能是百度关键词的推送，先logger，不做操作
            logger('53kf_cmd不存在'.json_encode($customer_data));
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
                    logger('53kf_系统未配置'.json_encode($complete_data));
                    return ['cmd'=>'OK','token'=>$customer_data['token']];
                }
                $token = $this->receive_53kf_message_info($complete_data,$customerService_config);

            }elseif ($customer_data['cmd'] == 'customer'){
                //客户信息的数据的推送
                $complete_data = json_decode(urldecode($customer_data['content']),true);//解析后的数据格式为：array
                //先去判断token是否在系统中
                $customerService_config = DB::table('customerservice_config')
                    ->where('account',$complete_data['worker_id'])
                    ->where('status','1')
                    ->where('token',$complete_data['token'])->first();
                if (empty($customerService_config)){
                    //此推送不在系统配置中,直接返回就好
                    logger('客户信息推送系统未配置'.json_encode($complete_data));
                    return ['cmd'=>'OK','token'=>$complete_data['token']];
                }
                $token = $this->receive_53kf_user_info($complete_data,$customerService_config);
                logger('记录下customer的'.json_encode($customer_data));
            }elseif($customer_data['cmd'] == 'activate'){
                //激活的推送
                logger('53kf_激活的推送');
                return ['cmd'=>'OK','token'=>$customer_data['token']];
            }else{
                logger('53kf_else:cmd不存在'.json_encode($customer_data));
                return ['cmd'=>'OK','token'=>''];
            }

        }
        return ['cmd'=>'OK','token'=>$token];
    }
    //接收53客服的整体的消息数据
    public function receive_53kf_message_info($data,$customerService_config){//para 数组
        logger('53kf_message_info'.json_encode($data));
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

        if (empty($origin_data)){
            //数据为空，新增一条记录
            DB::table('customerservice_record')->insert(
                ['config_id' => $customerService_config->id,
                    'data_guest_id' =>$data['session']['guest_id'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'data_message'=>json_encode($data_message),
                    'data_end'=>json_encode($data_end),
                    'data_session'=>json_encode($data_session),
                    'data_customer'=>json_encode([])
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
    public function receive_53kf_user_info($data,$customerService_config){//para 数组
        logger('53kf_user_info'.json_encode($data));
        $data_session = [];
        $data_end = [];
        $data_message = [];
        $origin_data = DB::table('customerservice_record')->where('config_id',$customerService_config->id)
            ->where('data_guest_id',$data['guest_id'])->first();
//        logger('receive_53kf_user_info进行数据的数据库'.json_encode($data));
        //判断此条数据是否存在，此数据必然更新或者插入了，那先去同步至同步系统中
        //判断是否同步到统计系统中
        if ($customerService_config->is_syn == '1'){
            //请求同步
            $syn_status = $this->syn_user_info_to_distribute_sys($customerService_config->id,$data);
        }else{
            //不等于1，不同步
            $syn_status = '0';
        }

        if (!empty($origin_data)){
            //数据已存在,只更新自我的字段就可以了
            DB::table('customerservice_record')
                ->where('id', $origin_data->id)
                ->update(['updated_at' => date('Y-m-d H:i:s'),
                    'syn_status'=>$syn_status,
                    'customer_weixin'=>$data['weixin'],
                    'customer_mobile'=>$data['mobile'],
                    'customer_remark'=>$data['remark'],
                    'customer_se'=>$data['se'],
                    'customer_kw'=>$data['kw'],
                    'customer_styleName'=>array_key_exists('style_name',$data)? $data['style_name']:'未知',
                    'data_customer'=>json_encode($data)
                ]);
        }else{
            //数据不存在，直接插入表中
            DB::table('customerservice_record')->insert(
                ['config_id' => $customerService_config->id,
                    'data_guest_id' =>$data['guest_id'],
                    'updated_at' => date('Y-m-d H:i:s'),
                    'data_message'=>json_encode($data_message),
                    'data_end'=>json_encode($data_end),
                    'data_session'=>json_encode($data_session),
                    'syn_status'=>$syn_status,
                    'customer_weixin'=>$data['weixin'],
                    'customer_mobile'=>$data['mobile'],
                    'customer_remark'=>$data['remark'],
                    'customer_se'=>$data['se'],
                    'customer_kw'=>$data['kw'],
                    'customer_styleName'=>array_key_exists('style_name',$data)? $data['style_name']:'未知',
                    'data_customer'=>json_encode($data)
                ]
            );
        }

        return $customerService_config->token;
    }

    //用于将数据直接同步至统计系统中
    public function syn_user_info_to_distribute_sys($customerService_configId,$data){
        //得到了相关的数据客服数据，进行统计系统的对接
        //不用判定是否已经存在，分配时会提示出来
        //根据客服系统的id来去取相关的统计系统的配置，看是否配置
        $date = date('Y-m-d H:i:s');
        $res_config_data = DB::table('res_config')->where('account_id', '=',$customerService_configId)
            ->where('type','=','53客服')->first();
        if (empty($res_config_data)){
            //统计系统的配置，未配置相关
            logger('客服同步统计系统的配置，未配置');
            return 0;
        }else{
            try {
                //查询到了统计配置相关的数据，组装数据插入到统计系统中
                $res_data_arr = ['user_id' => $res_config_data->user_id, 'config_id' => $res_config_data->id
                    ,'created_at'=>$date,'updated_at'=>$date
                    ,'belong'=>$data['style_name'],'type'=>$res_config_data->type,'data_json'=>json_encode($data)
                    ,'data_name'=>'未命名','data_phone'=>$data['mobile']];
                DB::table('res_data')->insert($res_data_arr);
                return 1;
            } catch (Exception $e) {
                logger('插入到统计系统中时，出错，返回0');
                return 0;
            }
        }
    }
}
