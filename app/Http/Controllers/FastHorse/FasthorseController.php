<?php

namespace App\Http\Controllers\FastHorse;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\models;
use mysql_xdevapi\Exception;

class FasthorseController extends Controller
{
    //获取快马的数据
    public function get_fast_horse (Request $request){
        date_default_timezone_set('Asia/Shanghai');
        logger('开始请求快马数据，开始处理数据');
        //开始处理传来的数据

        //第一步开始去查询快马的账号
        $config_data = DB::table('res_config')->where('status','=','1')->where('type','=','快马')->get();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无有效的快马账号');
            return json_encode(['code'=>-1,'message'=>'fail：接收方账号未配置']);
        }
        $endtime = date('Y-m-d H:i:s');
        //查询到快马的账号，进行数据的请求和存储
        foreach ($config_data as $config){
            //单独的快马账号，进行请求判定，判定总数是否需要二次访问
            //先去查询最近的一条数据
            $last_data = ResData::where('config_id', $config->id)->orderBy('id', 'desc')->first();
            $data = $this->get_all_kuaima_data($config->account,$config->account_password,$last_data,$endtime);
//            var_dump($data);
            if ($data['status'] == '1000'){

                //状态正常可以进行数据的记录
                $list = $data['data']['list'];
                //循环将数据插入到数据库中
                $res_data_arr = [];
                if (empty($list)){
                    //没有新数据
                    continue;
                }
                foreach ($list as $single_data){
                    $res_data_arr[] = ['user_id' => $config->user_id, 'config_id' => $config->id
                        ,'created_at'=>$endtime,'updated_at'=>$endtime
                        ,'belong'=>$config->belong,'type'=>$config->type,'data_json'=>json_encode($single_data)
                        ,'data_name'=>$single_data['name'],'data_phone'=>$single_data['tel']];
                }

                try {
                    DB::beginTransaction();
                        DB::table('res_data')->insert($res_data_arr);
                    DB::commit();
                }catch (Exception $e){
                    DB::rollBack();
                }

            }else{
                continue;
            }
        }


//        return json_encode(['code'=>0,'message'=>'success']);
    }

    //获取所有数据取消掉分页
    public function get_all_kuaima_data($appId,$appKey,$last_data,$endtime){
        if (empty($last_data)){
            //此账号的上一条记录为空，则首次请求，没有则以上一天的此时为标准
            $starttime = date("Y-m-d H:i:s",strtotime("-1 day"));
        }else{
            $starttime = $last_data->created_at;
        }
        $api_url = 'https://business.kmway.com/api/data/info?';
        $api_para = array("appId"=>$appId,"appKey"=>$appKey,'startTime'=>$starttime,'endTime'=>$endtime,'type'=>'2','pageIndex'=>1,'pageSize'=>80,'token'=>'');
        $api_url .= http_build_query($api_para,'','&');
        $data = file_get_contents($api_url);
        return json_decode($data,true);
    }
}
