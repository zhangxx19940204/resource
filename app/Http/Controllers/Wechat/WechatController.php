<?php

namespace App\Http\Controllers\Wechat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                    $img->img_url = env('APP_URL').'/upload/'.$img->img_url;
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
    public function get_single_search_img (Request $request){
        $para = $request->all();
        $img_list = DB::table('wechat_search_img')
            ->where('id', '=', $para['id'])
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
                    $img->img_url = env('APP_URL').'/upload/'.$img->img_url;
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

    /**
     * 注释:用户端微信授权登录
     */
    public function code2Session(Request $request)
    {
        Log::info('request code2session.');
        $app = app('wechat.mini_program.default');
        $code = $request->post("code",'');
        if (!$code || $code=='0') {
            return ['code'=>'-2','msg'=>'登录异常，重新登录'];
        }
        $appid  = env('WECHAT_MINI_PROGRAM_APPID','');
        $secret = env('WECHAT_MINI_PROGRAM_SECRET','');

        $url    = "https://api.weixin.qq.com/sns/jscode2session?appid=".$appid."&secret=".$secret."&js_code=".$code."&grant_type=authorization_code";
        $result = file_get_contents($url);
        $result = json_decode($result, true);


        if (!isset($result['openid'])) {
            Log::info($result);
            return ['code'=>'-2','message'=>'登录异常，重新登录:0123'];
        }
        if (!isset($result['unionid'])) {
            $result['unionid'] = "";
        }
//        Log::info($result);
        //插入数据库
        $ip = $request->ip();
        $user_id = 0;
        $is_get_user = 0;
        $userInfo = ['nickname'=>'','avatar'=>''];
        $user_info = DB::table('wx_user')->where('openid','=',$result['openid'])->first();
        if (empty($user_info)){
            //新用户注册，新增一条用户记录
            $user_id = DB::table('wx_user')->insertGetId(
                ['openid' => $result['openid'], 'loginip'=>$ip,'level'=>'1','created_at'=>date('Y-m-d H:i:s'),'status'=>'1','token'=>$result['session_key']]
            );

        }else{
            //老用户，更新信息，返回id
            DB::table('wx_user')
                ->where('id', $user_info->id)
                ->update(['loginip'=>$ip,'token'=>$result['session_key']]);
            $user_id = $user_info->id;
            $is_get_user = $user_info->is_get_user;
            if (empty($user_info->avatar)){
                $avatar = '';
            }else{
                $avatar = env('APP_URL').'storage/'.$user_info->avatar;
            }
            $userInfo = ['nickname'=>$user_info->nickname,'avatar'=>$avatar];
        }

        //返回相应的值
        return ['code'=>'0','msg'=>'登录成功',
            'user_id'=>$user_id,'token'=>$result['session_key'],'is_get_user'=>$is_get_user,
            'userInfo'=>$userInfo];
    }
    public function inspection_certification_status(Request $request){
        $user_info = DB::table('wx_user')->where('id','=',$request->user_id)->first();
        if($user_info->avatar){
            $user_info->avatar = env('APP_URL').'storage/'.$user_info->avatar;
        }else{
            $user_info->avatar = '';
        }
        if (empty($user_info->mobile) || empty($user_info->truename)){
            //姓名、身份证号、手机号  有一个为空，则跳出验证
            return ['code'=>'0','msg'=>'用户未认证','is_auth'=>'0'];
        }else{
            return ['code'=>'0','msg'=>'用户已认证','is_auth'=>'1'];
        }

    }

    public function add_user_info(Request $request){
        $status = DB::table('wx_user')
            ->where('id', $request->user_id)
            ->update(['mobile'=>$request->phone,'truename'=>$request->user_name]);
        Log::info('add_user_info:'.$status);
        if ($status){
            return ['code'=>'0','msg'=>'登录成功'];
        }else{
            return ['code'=>'1','msg'=>'登录失败，请重试'];
        }

    }
}
