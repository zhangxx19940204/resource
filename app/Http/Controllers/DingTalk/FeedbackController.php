<?php

namespace App\Http\Controllers\DingTalk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyDingTalk\Application;
use Log;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    //首页（资源反馈表）
    public function index(){
        $dingTalk_arr = config('dingTalk');
        $short_feedback_list = DB::table('short_feedback_relative')->get();
        $project_list = DB::table('dingding_project')->get();
        return view('dingTalk.feedback',['project_list'=>$project_list,'short_feedback_list'=>$short_feedback_list,'corp_id'=>$dingTalk_arr['corp_id']]);
    }

    //获取列表
    public function get_list(Request $request){
        $para = $request->all(); //array(3) { ["page"]=> string(1) "1" ["limit"]=> string(2) "10" ["user_id"]=> string(1) "5" }
        $page=$para['page']-1;
        if ($page != 0) {
            $page = $para['limit'] * $page;//从哪里开始
        }
        $data = DB::table('dingding_feedback')
            ->where('dingding_user_id', '=', $para['user_id'])
            ->offset($page)
            ->limit($para['limit'])
            ->orderBy('id', 'desc')
            ->get()->toarray();

        $count = DB::table('dingding_feedback')
            ->where('dingding_user_id', '=', $para['user_id'])
            ->count();
        // dd( DB::getQueryLog());
        return response()->json(['code'=>0,'msg'=>'获取成功','count'=>$count,'data'=>$data]);

    }

    //操作数据
    public function opera_data(Request $request){
        $para = $request->all();
        //判断类型
        if ($para['opera_type'] == 'edit') {

            $latest_data = $para['latest_data'];
            $latest_data['updated_at'] = date('Y-m-d H:i:s');
            $originally_data = $para['originally_data'];
            $opera_status =  DB::table('dingding_feedback')->where('id', $originally_data['id'])->update($latest_data);
            $res_info = ['code'=>0,'msg'=>'更新成功','data'=>$latest_data];

        }elseif ($para['opera_type'] == 'add') {

            $latest_data = $para['latest_data'];
            $latest_data['updated_at'] = date('Y-m-d H:i:s');
            $latest_data['dingding_user_id'] = $para['dingding_user_id'];
            $opera_status =  DB::table('dingding_feedback')->insert($latest_data);
            $res_info = ['code'=>0,'msg'=>'新增成功','data'=>$latest_data];

        }else {
            $res_info = ['code'=>0,'msg'=>'未知操作','data'=>[]];
        }

        //返回信息
        return response()->json($res_info);
    }

}
