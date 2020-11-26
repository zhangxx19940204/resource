<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

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

//EC的封装方法

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
