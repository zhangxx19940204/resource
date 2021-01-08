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
            ->orderByDesc('res_data.created_at')
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


}
