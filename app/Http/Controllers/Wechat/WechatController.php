<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WechatController extends Controller
{
    //
    public function get_search_img (Request $request){
        $para = $request->all();
        $img_list = DB::table('wechat_search_img')
            ->where('keywords', 'like', '%'.$para['key_word'].'%')
            ->where('status','=','1')
            ->get()->toArray();
        if(empty($img_list)){
            //数据为空
            return json_encode(['code'=>1,'message'=>'未查询到相关数据，请联系客服','list'=>$img_list]);
        }else{
            $res_arr = [];
            foreach ($img_list as $img){
                //判断头像是否需要加前缀
                if (!empty($img->img_url)){
                    //图片地址不为空
                    $img->img_url = env('APP_URL').'/upload'.$img->img_url;
                }else{
                    //图片地址为空，直接跳过
                    continue;
                }
                $res_arr[] = $img;
            }
            //循环完毕判断是否为空
            if(empty($res_arr)){
                return json_encode(['code'=>1,'message'=>'未查询到相关数据，请联系客服','list'=>[]]);
            }
            return json_encode(['code'=>0,'message'=>'已查询到','list'=>$res_arr]);
        }

    }
}
