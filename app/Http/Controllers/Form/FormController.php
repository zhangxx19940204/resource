<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\models;

class FormController extends Controller
{
    //获取问卷系统的数据
    public function get_form_data (Request $request){
        $para = $request->get('key');
        date_default_timezone_set('Asia/Shanghai');
        $page = $request->get('page','1');
        $pageSize = $request->get('limit','10');
        $start = ($page - 1) * $pageSize;
        logger('开始获取用户填写的数据');
        $user_obj = Auth::guard('admin')->user();
        $relation_form_user_list = DB::table('form_resource_relationship_user')->where('res_user_id','=',$user_obj->id)->get();
        if(empty($relation_form_user_list)){
            //未绑定
            return json_encode(['code'=>0,'message'=>'未绑定账号','count'=>0,'page'=>'','limit'=>'','data'=>[]]);
        }
        $form_user_arr = [];
        foreach ($relation_form_user_list as $form_user){
            $form_user_arr[] = $form_user->form_user_id;
        }
        $form_data_list = DB::connection('form_mysql')->select('SELECT fm_user_form_data.*,fm_user_form.`name` FROM `fm_user_form_data`
LEFT JOIN fm_user_form ON fm_user_form.`key` = fm_user_form_data.form_key WHERE fm_user_form_data.original_data LIKE '."'%".$para['search']."%'".' AND fm_user_form.user_id IN ('.implode(',',$form_user_arr).') limit '.$start.', '.$pageSize.';');
        $sub_data = [];
        // $form_key_list = [];//表单的key值列表
        foreach ($form_data_list as $form_data){
            // $form_key_list[] = $form_data->form_key;
            $original_arr = json_decode($form_data->original_data,true);
            $form_data->original_arr = $original_arr;
            $form_data->original_str = implode(',',$original_arr).';';
            $sub_data[] = $form_data;
        }
        // $form_item_list = DB::connection('form_mysql')->select('SELECT label,form_item_id,form_key FROM `fm_user_form_item` WHERE form_key IN ("'.implode('","',$form_key_list).'");');

        $data = $sub_data;
        $form_data_count = DB::connection('form_mysql')->select('SELECT fm_user_form_data.*,fm_user_form.`name` FROM `fm_user_form_data`
LEFT JOIN fm_user_form ON fm_user_form.`key` = fm_user_form_data.form_key WHERE fm_user_form_data.original_data LIKE '."'%".$para['search']."%'".' AND fm_user_form.user_id IN ('.implode(',',$form_user_arr).');');
        $count = count($form_data_count);
       return json_encode(['code'=>0,'message'=>'success','count'=>$count,'page'=>'','limit'=>'','data'=>$data]);
    }
}
