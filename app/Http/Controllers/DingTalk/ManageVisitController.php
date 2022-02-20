<?php

namespace App\Http\Controllers\DingTalk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use EasyDingTalk\Application;
use Illuminate\Support\Facades\DB;

class ManageVisitController extends Controller
{

    //管理来访进款表
    public function manage_visit(){
        $dingTalk_arr = config('dingTalk');
        $project_list = DB::table('dingding_project')->where('status','1')->get();
        return view('dingTalk.managevisit',['project_list'=>$project_list,'corp_id'=>$dingTalk_arr['corp_id']]);
    }
    //获取列表
    public function get_manage_visit_list(Request $request){
        $para = $request->all(); //array(3) { ["page"]=> string(1) "1" ["limit"]=> string(2) "10" ["user_id"]=> string(1) "5" }
        $page=$para['page']-1;
        if ($page != 0) {
            $page = $para['limit'] * $page;//从哪里开始
        }
        //先查询是否为管理员
        $manage_result = DB::table('dingding_manage_relative')->where('status','=','1')->where('manager_id','=',$para['user_id'])->first();
        $member_arr = [];
        if (empty($manage_result)){
            //没有查询到相关的数据
            $member_arr[] = $para['user_id'];
        }else{
            $member_arr = json_decode($manage_result->member_id_list);
            $member_arr[] = $para['user_id'];
        }

        $data = DB::table('dingding_visit')
            ->select('dingding_visit.*', 'dingding_user.name as dingding_user_name')
            ->leftJoin('dingding_user', 'dingding_visit.dingding_user_id', '=', 'dingding_user.id')
            ->whereIn('dingding_visit.dingding_user_id', $member_arr)
            ->offset($page)
            ->limit($para['limit'])
            ->orderBy('dingding_visit.id', 'desc')
            ->get()->toarray();

        $count = DB::table('dingding_visit')
            ->whereIn('dingding_visit.dingding_user_id', $member_arr)
            ->count();
        // dd( DB::getQueryLog());
        return response()->json(['code'=>0,'msg'=>'获取成功','count'=>$count,'data'=>$data]);

    }

    //操作数据
    public function opera_data(Request $request){
        $para = $request->all();
        $res_info = ['code'=>0,'msg'=>'此页面仅做展示所用','data'=>[]];
        //判断类型
//         if ($para['opera_type'] == 'edit') {
//
//             $latest_data = $para['latest_data'];
//             $latest_data['update_date'] = date('Y-m-d H:i:s');
//             $originally_data = $para['originally_data'];
//             $opera_status =  DB::table('dingding_visit')->where('id', $originally_data['id'])->update($latest_data);
//             $res_info = ['code'=>0,'msg'=>'更新成功','data'=>$latest_data];
//
//         }elseif ($para['opera_type'] == 'add') {
//
//             $latest_data = $para['latest_data'];
// //            $latest_data['create_date'] = date('Y-m-d H:i:s');
//             $latest_data['update_date'] = date('Y-m-d H:i:s');
//             $latest_data['dingding_user_id'] = $para['dingding_user_id'];
//             $opera_status =  DB::table('dingding_visit')->insert($latest_data);
//             $res_info = ['code'=>0,'msg'=>'新增成功','data'=>$latest_data];
//
//         }else {
//             $res_info = ['code'=>0,'msg'=>'未知操作','data'=>[]];
//         }

        //返回信息
        return response()->json($res_info);
    }

}
