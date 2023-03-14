<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ResData;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Content $content)
    {

        return $content->title('后台首页')
            ->description('图表展示')
            ->view('admin.home.show', []);
    }

    public function get_resource_all_by_day(Request $request){
        $request_data = $request->all();//['startDate'=>'2021-01-01','endDate'=>'2021-01-30'];//
        //求出此时间区间所有的有数据的日期
        $data = DB::table('res_data')
            ->select('res_data.*',DB::raw("date_format(res_data.created_at, '%Y-%m-%d') as created_date"),'res_config.custom_name')
            ->join('res_config', 'res_config.id', '=', 'res_data.config_id')
            ->whereBetween('res_data.created_at',[$request_data['startDate'],$request_data['endDate']])
//            ->orderByDesc('res_data.created_at')
            ->orderBy('res_data.created_at', 'asc')
            ->get();
        $legend_data = []; //账户列表
        $xAxis_arr = []; //过渡时间列表
        $xAxis_data = [];//时间列表
        $series_arr = [];//总数据的过度格式
        $series_data = []; //
        //第一步先去取出所有的x轴的数据（时间列表）
        foreach ($data as $single_data){
            if (!array_key_exists($single_data->created_date,$xAxis_arr)){
                //日期是否存在，不存在就加上
                $xAxis_arr[$single_data->created_date] = 0;
            }
        }
        //获取已知的时间列表,为每一个类加上数据的统计（这里是根据不同账号来统计的）
        foreach ($data as $single_data){
            if (!array_key_exists($single_data->custom_name,$series_arr)){
                //账户信息是否存在，不存在就加上
                $legend_data[] = $single_data->custom_name;
                $series_arr[$single_data->custom_name] = $xAxis_arr;
            }else{
                //账号信息已存在，不进行操作
            }
            //确保账号和日期存在，接下来，对数据数量进行加减运算
            $series_arr[$single_data->custom_name][$single_data->created_date]++;
        }
        //已获得相应数据，进行最后数据的整理
        $xAxis_data = array_keys($xAxis_arr);
        foreach ($series_arr as $key_series=>$single_series){
            $total_arr = array_values($single_series);
            $series_data[] = ['name'=>$key_series, 'type'=> 'line', 'stack'=>'总量', 'data'=>$total_arr];
        }
//        dump($xAxis_arr,$series_arr,$series_data);
        return response()->json([
            'legend_data' => $legend_data,
            'xAxis_data' => $xAxis_data,
            'series_data' => $series_data,
        ]);
    }

    public function show_form_data(Content $content){

        return $content->title('问卷表单展示')
            ->description('问卷表单展示')
            ->view('admin.form.show', []);
    }
    public function print_form_data(Content $content,$id=0){
        $form_data = DB::connection('form_mysql')->select('SELECT fm_user_form_data.*,fm_user_form.`name` FROM `fm_user_form_data`
LEFT JOIN fm_user_form ON fm_user_form.`key` = fm_user_form_data.form_key WHERE fm_user_form_data.id = '.$id.';');
        if (empty($form_data)){
            $res_data = ['form_data_arr'=>'','title'=>''];
        }else{
            $original_data = json_decode($form_data[0]->original_data,true);
            $form_items = DB::connection('form_mysql')->select('SELECT * FROM `fm_user_form_item` WHERE form_key = "'.$form_data[0]->form_key.'";');
            $form_item_arr = [];
            $form_data_arr = [];
            foreach ($form_items as $key=>$form_item){
                $form_item_arr[$form_item->form_item_id] = $form_item;
            }
            foreach ($original_data as $k=>$v){

                if (array_key_exists($k,$form_item_arr)){
                    //表单字段存在,判断是否为展示字段
                    if($form_item_arr[$k]->is_display_type == '1'){
                        //展示字段，过滤
                        continue;
                    }
                    //表单字段存在，并为填写字段，进行展示
                    $form_data_arr[] = ['value'=>$v,'label'=>$form_item_arr[$k]->label];
                }else{
                    //表单字段不存在，直接不展示
                    continue;
                }
            }
            $res_data = ['form_data_arr'=>$form_data_arr,'title'=>$form_data[0]->name];
        }
        return $content->title('问卷表单打印')
            ->description('问卷表单打印')
            ->view('admin.form.print', $res_data);
    }

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
