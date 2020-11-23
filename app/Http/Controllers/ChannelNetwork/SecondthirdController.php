<?php

namespace App\Http\Controllers\ChannelNetwork;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;
use App\models;

class SecondthirdController extends Controller
{
    //
    //获取渠道网络的实时23网的数据
    public function get_second_third (Request $request){
        date_default_timezone_set('Asia/Shanghai');
        logger('开始请求23网数据，开始处理数据');
        //开始处理传来的数据

        //第一步开始去查询23网的账号
        $config_data = DB::table('res_config')->where('status','=','1')->where('type','=','23网')->get();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无有效的23网账号');
            return json_encode(['code'=>-1,'message'=>'fail：接收方账号未配置']);
        }
        $api_url = 'https://guest.qudao.com/api/data';
        //查询到23网的账号，进行数据的请求和存储
        foreach ($config_data as $config){
             //单独的23网账号，进行请求判定
            $datetime = date('Y-m-d H:i:s');
             $tomorrow_datetime = date('Y-m-d H:i:s');
             $date = date('Y-m-d'); //开始操作今天的数据
             $ctime= time();
             $para = ['openkey'=>$config->account,'token'=>md5($config->account_password.$ctime.$date.'tj'),
                 'ac'=>'tj',
                 'ctime'=>$ctime,
                 'date'=>$date
                 ];
             $tj_data_str = $this->simple_post($api_url,$para);
             $tj_data_arr = json_decode($tj_data_str,true);//数组  今天的所有数据，通过判断data_id判断是否已经存在数据库中
            if (array_key_exists("status",$tj_data_arr)){
                //判断status存在，直接跳过
                logger('status为：'.$tj_data_arr['status'].'，23网;账号异常账号Id:'.$config->id);
                continue;
            }
            //账号数据应该正常
            //将今天的数据进行整合用data_id做成一个数组
            $original_data_id = [];
            foreach ($tj_data_arr as $tj_data){
                $original_data_id[] = $tj_data['data_id'];
            }
            //查询当前日期的数据，拿到data_id,来确定哪些加到数据库中
//            DB::connection()->enableQueryLog();  // 开启QueryLog

            $last_save_arr_collection = DB::table('res_data')
                ->where('config_id', '=',$config->id)
                ->whereBetween('data_json->datetime', [date('Y-m-d').' 00:00:00',date("Y-m-d",strtotime("+1 day")).' 00:00:00'])
                ->pluck('data_json')->toArray();
            $last_save_arr = [];
            foreach ($last_save_arr_collection as $value) {
                $value_arr = json_decode($value,true);
                $last_save_arr[] = $value_arr['data_id'];
            }

//            echo '<pre>';
//            var_dump(DB::getQueryLog(),$last_save_arr);
//            echo '</pre>';
//            die();


            if (empty($last_save_arr)){
                //今天还未有数据
            }
            //今天已有数据
            $ought_add_dataId_arr = array_diff($original_data_id,$last_save_arr);//应该添加的data_id的数据

//            echo '<pre>';
//            var_dump($original_data_id,$ought_add_dataId_arr,$last_save_arr);
//            echo '</pre>';
//            die();

            $add_data_arr = [];
            foreach ($ought_add_dataId_arr as $new_dataId){

                $xg_para = ['openkey'=>$config->account,'token'=>md5($config->account_password.$ctime.$new_dataId.'xg'),
                    'ac'=>'xg',
                    'ctime'=>$ctime,
                    'date'=>$date,
                    'data_id'=>$new_dataId
                ];
                $xg_data_str = $this->simple_post($api_url,$xg_para);
                $xg_data_arr = json_decode($xg_data_str,true);//数组  今天的某个数据的详情
//                echo '<pre>';
//
//                var_dump($xg_data_arr);
//                echo '</pre>';
//                die();
                if ($xg_data_arr['sta'] != '100'){
                    //调用失败，记录一下
                    logger('查询单个详情失败状态ID：'.$new_dataId.'失败状态：'.$xg_data_arr['status']);
                    continue;
                }else{
                    //调用成功，记录数据，准备一次向插入数据库
                    $add_data_arr[] = ['user_id' => $config->user_id, 'config_id' => $config->id
                        ,'created_at'=>$datetime,'updated_at'=>$datetime
                        ,'belong'=>$config->belong,'type'=>$config->type,'data_json'=>json_encode($xg_data_arr['data'])
                        ,'data_name'=>(array_key_exists('uname',$xg_data_arr['data'])?$xg_data_arr['data']['uname']:'')
                        ,'data_phone'=>$xg_data_arr['data']['phone']];

                }

            }
            //插入数据库
//            var_dump('插入数据库的数组：',$add_data_arr);
//            die();
            try {
                DB::beginTransaction();
                DB::table('res_data')->insert($add_data_arr);
                DB::commit();
            }catch (Exception $e){
                DB::rollBack();
            }

            logger(date('Y-m-d H:i:s').'23网账号：'.$config->id.'正常');
        }


//        return json_encode(['code'=>0,'message'=>'success']);
    }

}
