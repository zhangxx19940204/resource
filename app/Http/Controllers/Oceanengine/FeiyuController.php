<?php

namespace App\Http\Controllers\Oceanengine;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FeiyuController extends Controller
{
    //接收飞鱼传送过来的数据
    public function receive_fei_oceanengine (Request $request){
        logger('接收的数据');
        logger($request->all());
        return ['code'=>0,'message'=>'success'];
    }

}
