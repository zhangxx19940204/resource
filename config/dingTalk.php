<?php

return [
    /*
    |-----------------------------------------------------------
    | 【必填】企业 corpId
    |-----------------------------------------------------------
    */
    'corp_id' => 'ding72571b91c47e745235c2f4657eb6378f',

    /*
    |-----------------------------------------------------------
    | 【必填】应用 AppKey
    |-----------------------------------------------------------
    */
    'app_key' => 'dings5ylsqdwfmzzu8d6',

    /*
    |-----------------------------------------------------------
    | 【必填】应用 AppSecret
    |-----------------------------------------------------------
    */
    'app_secret' => 'rS-RGiK2YTCkVBWXbb4tWgDiKSdXX15LHy38p9Ujr1yedRhedDtMZEHEWPUj_rmr',

//    自定义了一个链接集合
    'gettoken_url'=>'https://oapi.dingtalk.com/gettoken',//获取token的url
    'getuserinfo_url'=>'https://oapi.dingtalk.com/topapi/v2/user/getusserinfo',//获取用户信息的url
    'getuserdetail_url'=>'https://oapi.dingtalk.com/topapi/v2/user/get',//获取用户详细信息的url
    'getdepartmentdetail_url'=>'https://oapi.dingtalk.com/topapi/v2/department/get',//获取部门的详细信息的url

    /*
    |-----------------------------------------------------------
    | 【选填】加解密
    |-----------------------------------------------------------
    | 此处的 `token` 和 `aes_key` 用于事件通知的加解密
    | 如果你用到事件回调功能，需要配置该两项
    */
    'token' => 'uhl3CZbtsmf93bFPanmMenhWwrqbSwPc',
    'aes_key' => 'qZEOmHU2qYYk6n6vqLfi3FAhcp9bGA2kgbfnsXDrGgN',

    /*
    |-----------------------------------------------------------
    | 【选填】后台免登配置信息
    |-----------------------------------------------------------
    | 如果你用到应用管理后台免登功能，需要配置该项
    */
    'sso_secret' => 'Fx9_i5dSW5tpGtjalksdf98JF8uj32xb4NJQR5G9-VSchasd98asfdMmLR',

    /*
    |-----------------------------------------------------------
    | 【选填】第三方网站 OAuth 授权
    |-----------------------------------------------------------
    | 如果你用到扫码登录、钉钉内免登和密码登录第三方网站，需要配置该项
    */
    'oauth' => [
        /*
        |-------------------------------------------
         | `app-01` 为你自定义的名称，不要重复即可
         |-------------------------------------------
         | 数组内需要配置 `client_id`, `client_secret`, `scope` 和 `redirect` 四项
         |
         | `client_id` 为钉钉登录应用的 `appId`
         | `client_secret` 为钉钉登录应用的 `appSecret`
         | `scope`:
         |     - 扫码登录第三方网站和密码登录第三方网站填写 `snsapi_login`
         |     - 钉钉内免登第三方网站填写 `snsapi_auth`
         | `redirect` 为回调地址
         */
        'app-01' => [
            'client_id' => 'dingoaxmia0afj234f7',
            'client_secret' => 'c4x4el0M6JqMC3VQP80-cFasdf98902jklFSUVdAOIfasdo98a2',
            'scope' => 'snsapi_login',
            'redirect' => 'https://easydingtalk.org/callback',
        ],
        /*
        |-------------------------------------------
         | 可配置多个 OAuth 应用，数组内内容同上
         |-------------------------------------------
         */
        'app-02' => [
            // ...
        ]
    ]
];
