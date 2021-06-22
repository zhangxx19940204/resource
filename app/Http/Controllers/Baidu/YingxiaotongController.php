<?php

namespace App\Http\Controllers\Baidu;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class YingxiaotongController extends Controller
{
    //接收百度营销通发送的数据
    public function receive_baidu_yingxiaotong (Request $request){
        $para = $request->all();
        logger('接收百度营销通发送的数据');
        logger($para);
        return '';
        //开始处理传来的数据
        date_default_timezone_set('Asia/Shanghai');

        //第一步开始去查询相应的账号对应
        if(!array_key_exists('adv_id',$para)){
            //adv_id 不存在
            logger('error参数有误:'.json_encode($para));
            return json_encode(['code'=>-1,'message'=>'fail：参数有误（adv_id）']);
        }
        $config_data = DB::table('res_config')->where('account_id', '=', $para['adv_id'])->where('status','=','1')->first();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无对应有效账号:'.$para['adv_name'].','.$para['adv_id']);
            return json_encode(['code'=>-1,'message'=>'fail：接收方账号未配置']);
        }

        //查询到飞鱼的账号，进行数据的整理和存储
        $ResData = new ResData;
        $ResData->user_id = $config_data->user_id;
        $ResData->config_id = $config_data->id;
        $ResData->created_at = date('Y-m-d H:i:s');
        $ResData->updated_at = date('Y-m-d H:i:s');
        $ResData->remarks = $config_data->remarks;
        $ResData->belong = $config_data->belong;
        $ResData->type = $config_data->type;
        $ResData->data_json = json_encode($para);
        $ResData->data_name = $para['name'];
        $ResData->data_phone = $para['telphone'];
        $ResData->save();

        return json_encode(['code'=>0,'message'=>'success']);
    }
}
