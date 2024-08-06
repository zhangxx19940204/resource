<?php

namespace App\Http\Controllers\MengZhao;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\models;

class MengZhaoController extends Controller
{
    //
    public function receive_mengzhao (Request $request){
        $para = $request->all();
        logger('接收盟招网传来的数据');
        logger($para);
        //开始处理传来的数据
        date_default_timezone_set('Asia/Shanghai');


        //第一步开始去查询相应的账号对应
        if(!array_key_exists('projectId',$para)){
            //projectId 不存在
            logger('error参数有误:'.json_encode($para));
            return json_encode(['Errorcode'=>1003,'Errormsg'=>'projectId参数不存在']);
        }
        $config_data = DB::table('res_config')->where('account_id', '=', $para['projectId'])
            ->where('type','=','盟招网')
            ->where('status','=','1')->first();
        if (empty($config_data)){
            //没有对应的生效账号
            logger('无对应有效账号:'.$para['projectId'].','.$para['projectName']);
            return json_encode(['Errorcode'=>1003,'Errormsg'=>'未配置相关账号，联系技术负责人']);
        }

        //查询到盟招网的账号，进行数据的整理和存储
        $ResData = new ResData;
        $ResData->user_id = $config_data->user_id;
        $ResData->config_id = $config_data->id;
        $ResData->created_at = date('Y-m-d H:i:s');
        $ResData->updated_at = date('Y-m-d H:i:s');
        $ResData->remarks = $config_data->remarks;
        $ResData->belong = $config_data->belong;
        $ResData->type = $config_data->type;
        $ResData->data_json = json_encode($para);
        $ResData->data_name = array_key_exists('name',$para)?$para['name']:'暂无';
        $ResData->data_phone = array_key_exists('tel',$para)?$para['tel']:'';
        $ResData->save();

        return json_encode(['Errorcode'=>1001,'Errormsg'=>'成功导入']);
    }
}
