<?php

namespace App\Http\Controllers\CustomerService;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FiveThreeController extends Controller
{
    //接收53kf 的消息数据
    public function receive_53kf_message_info(Request $request){
        logger('receive_53kf_message_info:'.json_encode($request->all()));
        return ['cmd'=>'OK','token'=>'rH0D52587Wt54mFok'];
    }
    //接收53客服的客户消息
    public function receive_53kf_user_info(){

    }
}
