<?php

namespace App\Http\Controllers\GlobalJoin;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobaljoinController extends Controller
{
    //接收全球加盟网传送过来的数据
    public function receive_global_join (Request $request){
        date_default_timezone_set('Asia/Shanghai');
        $para = $request->all();
        logger('接收全球加盟网传来的数据');
        logger($para);
//        logger('请求方式'.$request->method());
//        logger('header：'.json_encode($request->header()));
//        logger('cookie：'.json_encode($request->cookie()));
//        logger('url：'.$request->path());

        //开始处理传来的数据

        //第一步开始去查询相应的账号对应
        $config_data = DB::table('res_config')->where('account', '=', $para['data']['login_name' ])->where('status','=','1')->first();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无对应有效账号:');
            return json_encode(['code'=>-1,'message'=>'fail：接收方账号未配置']);
        }

        //查询到全球的账号，进行数据的整理和存储
        $ResData = new ResData;
        $ResData->user_id = $config_data->user_id;
        $ResData->config_id = $config_data->id;
        $ResData->created_at = date('Y-m-d H:i:s');
        $ResData->updated_at = date('Y-m-d H:i:s');
        $ResData->belong = $config_data->belong;
        $ResData->type = $config_data->type;
        $ResData->data_json = json_encode($para['data']);
        $ResData->data_name = $para['data']['msg_name'];
        $ResData->data_phone = $para['data']['telephone'];

        if ($ResData->save()){
            //创建成功
            return ['success'=>'成功','msg'=>'成功','code'=>200,'response_code'=>0];
        }else {
            return ['success'=>'失败','msg'=>'失败','code'=>400,'response_code'=>0];
        }


    }
}
