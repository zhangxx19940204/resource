<?php

namespace App\Http\Controllers;

//use Illuminate\Filesystem\Cache;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 发送简单的post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    public function simple_post($url, $post_data)
    {
        $postData = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postData,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;

    }

    public function simple_get($url, $get_data){
        $ch = curl_init();
        $timeout = 300;
        $get_data_para = implode('&',$get_data);
        $complete_url = $url.'?'.$get_data_para;
        curl_setopt ($ch, CURLOPT_URL, $complete_url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file_contents = curl_exec($ch);
        curl_close($ch);

        return $file_contents;
    }

//    封装的dingTalk的方法
    //钉钉用户登录接口
    public function user_login(Request $request){

        $code = $request->get('code', '');
        if ($code == '') {
            //code值为空
            return response()->json(['status'=>-1,'message'=>'登录异常，请重新登录','data'=>[]]);
        } else {
            //code正常
            $app_config = config('dingTalk');
//            $app = new Application($app_config);
//            $user_id_arr = $app->user->getUserByCode($code); //{"errcode":0,"sys_level":0,"is_sys":false,"name":"张祥祥","errmsg":"ok","deviceId":"693ef736987bb9c1cb2df1294d58b8a3","userid":"100700404824396736"}
            $access_token = $this->get_dingTalk_access_token($app_config,$app_config['corp_id']);
            if (empty($access_token)){
                return response()->json(['status'=>-1,'message'=>'系统异常，请重新登录——ak','data'=>[]]);
            }
            $url = $app_config['getuserinfo_url'].'?access_token='.$access_token;
            $user_id_json = $this->simple_post($url,['code'=>$code]);
            $user_id_arr = json_decode($user_id_json,true);
            logger($code,$user_id_arr);

            if ($user_id_arr['errcode'] != '0'){
                return response()->json(['status'=>-1,'message'=>'登录异常2，请重新登录','data'=>[]]);
            }

            //判断userid是否存在
            $single_user = DB::table('dingding_user')->where('userid', '=',$user_id_arr['result']['userid'])->first();

            if(empty($single_user)){
                //新用户，进行插入操作

                $user_detail = $this->get_user_detail($app_config,$user_id_arr['result']['userid'],$access_token);

                if ($user_detail['status'] != '0'){
                    //不正常
                    return response()->json(['status'=>-1,'message'=>$user_detail['message'],'data'=>[]]);
                }

                //正常，操作
                $user_id = DB::table('dingding_user')->insertGetId($user_detail['data']);


                if ($user_id > '0'){
                    $res_data = $user_detail['data'];
                    $res_data['id'] = $user_id;
                    return response()->json(['status'=>1,'message'=>'正常登录','data'=>$res_data]);
                }else{
                    return response()->json(['status'=>-1,'message'=>'登录异常4，请重新登录','data'=>[]]);
                }
            }else{
                //查询到了用户，判断状态是否需要更新
                if ($single_user->status == '1'){
                    //无需更新
                    return response()->json(['status'=>1,'message'=>'登录成功1','data'=>$single_user]);
                }else{
                    //需要更新数据
                    $user_detail = $this->get_user_detail($app_config,$user_id_arr['result']['userid'],$access_token);
                    if ($user_detail['status'] != '0'){
                        //不正常
                        return response()->json(['status'=>-1,'message'=>$user_detail['message'],'data'=>[]]);
                    }
                    //正常，更新操作
                    $update_status = DB::table('dingding_user')->where('id', '=',$single_user->id)->update($user_detail['data']);
                    $update_after_data = DB::table('dingding_user')->where('userid', '=',$user_id_arr['userid'])->first();
                    return response()->json(['status'=>1,'message'=>'登录成功2','data'=>$update_after_data]);
                }
            }

        }

    }

    //钉钉获取$access_token定时
    public function get_dingTalk_access_token($app_config,$corp_id){

        if (Cache::has($corp_id.'_ak')) {
            //access_token存在，直接返回
            return Cache::get($corp_id.'_ak');
        }else{
            //access_token不存在，直接请求获取并记录
            $url = $app_config['gettoken_url'];
            $access_token_json = $this->simple_get($url,['appkey'=>$app_config['app_key'],'appsecret'=>$app_config['app_secret']]);
            $access_token_arr = json_decode($access_token_json,true);
            if ($access_token_arr['errcode'] == '0'){
                //获取成功，记录进缓存中,同时返回去
                Cache::put($corp_id.'_ak', $access_token_arr['access_token'], 60*60);
                return $access_token_arr['access_token'];
            }else{
                //出错了，提示
                return '';
            }
        }

    }

    //获取钉钉用户的详情和部门
    public function get_user_detail($app_config,$userid,$access_token){
        //查詢用户详情和部门列表更新
        $user_detail_json = $this->simple_post($app_config['getuserdetail_url'].'?access_token='.$access_token,['userid'=>$userid]);
        $user_detail_arr = json_decode($user_detail_json,true);
        if ($user_detail_arr['errcode'] != '0'){
            return ['status'=>-1,'message'=>'登录异常3，请重新登录','data'=>[]];
        }
        //去获取部门详情作为参数
        $department_name = '';
        $department_id_str = '';
        $department_detail_url = $app_config['getdepartmentdetail_url'];
        foreach ($user_detail_arr['department'] as $key=>$department_id){
            $department_id_str .= $department_id.',';
            $single_department_json = $this->simple_post($department_detail_url.'?access_token='.$access_token,['dept_id'=>$department_id]);
            $single_department = json_decode($single_department_json,true);
            if ($single_department['errcode'] != '0'){
                continue;
            }
            $department_name .= $single_department['result']['name'].'-';
        }
        //下面组装用户的信息装备进入数据库
        $data = ['userid' => $user_detail_arr['result']['userid'],
//            'openid' => $user_detail_arr['openId'],
            'unionid' => $user_detail_arr['result']['unionid'],
            'name' => $user_detail_arr['result']['name'],
            'mobile' => $user_detail_arr['result']['mobile'],
            'position' => '未知',
            'avatar' => $user_detail_arr['result']['avatar'],
            'department_id' => $department_id_str,
            'department_name' => $department_name,
            'status' => '1',
            'create_date' => date("Y-m-d H:i:s")
        ];

        return ['status'=>0,'message'=>'正常','data'=>$data];

    }
    /**
     * 发送post请求
     * @param string $url 请求地址
     * @param array $post_data post键值对数据
     * @return string
     */
    public function send_post($url, $post_data) {

        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;

    }

//  EC的封装方法

    /**
     *  签名算法
     *  该方法可以公用
     * @param int $timeStamp
     * @param string $appId
     * @param string $appSecret
     * @return string 返回签名数据
     */
    function getSign($timeStamp, $appId, $appSecret)
    {
        $sign = "appId={$appId}&appSecret={$appSecret}&timeStamp={$timeStamp}";
        return strtoupper(md5($sign));
    }

    /**
     * get 请求业务
     * 该方法可以公用
     *
     * @param string $url
     * @param string $jsonData
     * @param int $cid
     * @param string $appId
     * @param string $appSecret
     */
    function http_get($url, $cid, $appId, $appSecret,$method = 'GET',$psot_data =[])
    {
        // 1. 获取当前时间戳
        $timeStamp = time() * 1000;
        // 2. 获取签名
        $sign = $this->getSign($timeStamp, $appId, $appSecret);
        // 3. 封装请求头
        $head = array(
            'Content-Type: application/json; charset=utf-8',
            'X-Ec-Cid: ' . $cid,
            'X-Ec-Sign: ' . $sign,
            'X-Ec-TimeStamp: ' . $timeStamp
        );
        // 3. 传入http 参数
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // https 支持 - 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        // head
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);

        if($method == 'POST'){
            //设置post方式提交
            curl_setopt($ch, CURLOPT_POST, 1);
            //设置post数据
            $post_data = json_encode($psot_data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }


        // 请求服务器
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        // 组织，返回结果和响应码
        return $response;
    }

    /**
     * 该方法可以公用
     * 将参数拼接到url地址中
     * @param string $url
     * @param string $params
     * @return string|string|string
     */
    function addParmasToUrl($url, $params)
    {
        $urlParmas = $url;
        if (empty($params)) {
            return $urlParmas;
        }
        $isFist = true;
        foreach ($params as $key => $val) {
            if ($isFist) {
                $urlParmas = $urlParmas . "?" . $key . "=" . $val;
                $isFist = false;
            } else {
                $urlParmas = $urlParmas . "&" . $key . "=" . $val;
            }
        }
        return $urlParmas;
    }


}
