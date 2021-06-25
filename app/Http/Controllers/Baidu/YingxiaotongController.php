<?php

namespace App\Http\Controllers\Baidu;

use App\Http\Controllers\Controller;
use App\Models\ResData;
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
        //开始处理传来的数据
        date_default_timezone_set('Asia/Shanghai');

        //第一步开始去查询相应的账号对应
        if(!array_key_exists('pageId',$para) || !array_key_exists('ucid',$para)){
            //账号id和页面id 不存在
            logger('接收百度营销通发送的数据error参数有误:'.json_encode($para));
            return json_encode(['code'=>-1,'message'=>'fail：参数有误（adv_id）']);
        }
        $config_data = DB::table('res_config')
            ->where('account_id', '=', $para['ucid'])
            ->where('account', '=', $para['pageId'])
            ->where('status','=','1')
            ->first();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无对应有效账号:'.$para['ucid'].','.$para['pageId']);
            return json_encode(['code'=>-1,'message'=>'fail：百度营销通接收方账号未配置']);
        }
        //查询到百度营销通的账号，进行数据的整理和存储
        $ResData = new ResData;
        $ResData->user_id = $config_data->user_id;
        $ResData->config_id = $config_data->id;
        $ResData->created_at = date('Y-m-d H:i:s');
        $ResData->updated_at = date('Y-m-d H:i:s');
        $ResData->remarks = $config_data->remarks;
        $ResData->belong = $config_data->belong;
        $ResData->type = $config_data->type;
        $ResData->data_json = json_encode($para);
        $name_key = array_search('name', array_column($para['formDetail'], 'type'));
        if (is_numeric($name_key)){
            //知道key值了，去取值
            $ResData->data_name = $para['formDetail'][$name_key]['value'];
        }else{
            //未查询到name字段，没有名字
            $ResData->data_name = '未知';
        }
        $ResData->data_phone = trim($para['cluePhoneNumber']);
        $ResData->save();

        return json_encode(['code'=>0,'message'=>'success']);
    }
}
