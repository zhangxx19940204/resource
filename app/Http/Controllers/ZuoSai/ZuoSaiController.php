<?php

namespace App\Http\Controllers\ZuoSai;

use App\Http\Controllers\Controller;
use App\Models\ResConfig;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class ZuoSaiController extends Controller
{
    // 5988
    public function get_5988_data(){
        date_default_timezone_set('Asia/Shanghai');
        //去获取5988的账号里的数据
        $res_config_arr = ResConfig::where('status','1')->where('type','=','5988')->get();
        $uri = env('ZUOSAI','https://i.5988.com/api');
        if (empty($res_config_arr)){
            logger('有效的5988账号为空');
            return '有效的5988账号为空';
        }
        $uri = env('ZUOSAI','https://i.5988.com/api');
        $total_insert_data = [];
        foreach ($res_config_arr as $single_res_config){
            //开始循环单个5988账号
            $app_key = $single_res_config->account;
            $app_secret = $single_res_config->account_password;
            $project_id = $single_res_config->account_id;
            $action = 'getDatas';
            $timestamp = time();
            $sign = strtoupper(md5(md5($timestamp.md5(md5($app_secret)))));
            $post_data = ['sign'=>$sign,'appkey'=>$app_key,'timestamp'=>$timestamp,'action'=>$action,'project_id'=>$project_id,'prepage'=>50,'page'=>1];
            $data = $this->simple_post($uri,$post_data);
            $deal_data_arr = json_decode($data,true);//需要处理的数据数组
            if (empty($deal_data_arr['data'])){
                logger('5988未有有效数据:'.$single_res_config->custom_name);
                continue;
            }
            //已请求到5988的数据，现在进行判断哪些需要导入
            $last_data = ResData::where('config_id','=',$single_res_config->id)->orderBy('data_json->sent_at','desc')->first();
            //查询相关数据的最新一条
            if (empty($last_data)){
                //上一条数据为空，第一次加载数据，只取其中最新的一条
                $first_data = $deal_data_arr['data'][0];
                $total_insert_data[] = ['user_id' => $single_res_config->user_id, 'config_id' => $single_res_config->id
                    ,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')
                    ,'belong'=>$single_res_config->belong,'type'=>$single_res_config->type,'data_json'=>json_encode($first_data)
                    ,'data_name'=>$first_data['name'],'data_phone'=>$first_data['tel']];
                logger('第一次请求');
                continue;
            }
            //不是第一次请求了，判断哪些需要在加进去，判断时间大小
            logger('非第一次请求上一次请求的时间：');
            $last_data_json_arr = json_decode($last_data->data_json,true);
            $last_time = strtotime($last_data_json_arr['sent_at']);//上一次的数据时间
            logger($last_time);
            foreach ($deal_data_arr['data'] as $single_data){
                if (strtotime($single_data['sent_at']) <= $last_time){
                    continue;
                }
                $total_insert_data[] = ['user_id' => $single_res_config->user_id, 'config_id' => $single_res_config->id
                    ,'created_at'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')
                    ,'belong'=>$single_res_config->belong,'type'=>$single_res_config->type,'data_json'=>json_encode($single_data)
                    ,'data_name'=>$single_data['name'],'data_phone'=>$single_data['tel']];
            }
        }
//        echo '<pre>';
//        var_dump($last_data,$total_insert_data);
//        echo '</pre>';
//        die();

        //下面进行数据的插入
        try {
            DB::beginTransaction();
            DB::table('res_data')->insert($total_insert_data);

            DB::commit();
        }catch (Exception $e){
            DB::rollBack();
        }

        logger('5988数据导入完成');

    }
}
