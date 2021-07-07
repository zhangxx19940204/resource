<?php

namespace App\Http\Controllers\Weixin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WeixnAdvertisementController extends Controller
{
    //接收微信广告发送的数据
    public function receive_weixin_adv (Request $request){
        $para = $request->all();
        logger('接收微信广告发送的数据');
        logger($para);
        //开始处理传来的数据
        date_default_timezone_set('Asia/Shanghai');
        //第一步开始去查询相应的账号对应
        if(!array_key_exists('advertiser_id',$para) || !array_key_exists('account_id',$para)){
            //账号id和公众号id 不存在
            logger('接收微信广告发送的数据error参数有误:'.json_encode($para));
            return json_encode(['code'=>-1,'message'=>'fail：参数有误（advertiser_id和account_id）']);
        }
        $config_data = DB::table('res_config')
            ->where('account_id', '=', $para['account_id'])
            ->where('account', '=', $para['advertiser_id'])
            ->where('status','=','1')
            ->first();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无对应有效账号:advertiser_id：'.$para['advertiser_id'].',account_id：'.$para['account_id']);
            return json_encode(['code'=>-1,'message'=>'fail：微信广告接收方账号未配置']);
        }
        //查询到微信广告的账号，进行数据的整理和存储
        $ResData = new ResData;
        $ResData->user_id = $config_data->user_id;
        $ResData->config_id = $config_data->id;
        $ResData->created_at = date('Y-m-d H:i:s');
        $ResData->updated_at = date('Y-m-d H:i:s');
        $ResData->remarks = $config_data->remarks;
        $ResData->belong = $config_data->belong;
        $ResData->type = $config_data->type;
        $ResData->data_json = json_encode($para);
        $ResData->data_name = $para['leads_name'];
        $ResData->data_phone = trim($para['leads_tel']);
        $ResData->save();
        return json_encode(['code'=>0,'message'=>'success']);
    }
}
