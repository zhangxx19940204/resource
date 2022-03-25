<?php

namespace App\Http\Controllers\DingTalk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DingCallbackCrypto;

class CallBackController extends Controller
{
    //接收check_url事件
    public function receive_dingtalk_event(Request $request){

        logger('事件推送的url'.$request->fullUrl());
        $encrypt = $request->get('encrypt','');
        $signature = $request->get('signature','');
        $timestamp = $request->get('timestamp','');
        $nonce = $request->get('nonce','');
        $msg_signature = $request->get('msg_signature','');
        $crypt = new DingCallbackCrypto("eXCjpvtXFjVEajtEmgFYhokNDtqbZUT7t954GDK", "o6W8zKoCnmyNvLpoL689xfgbzoz2r2k7V4FBaxAiSXY", "dingcibjfzwphqk9foye");
        $text = $crypt->getDecryptMsg($signature, $timestamp, $nonce, $encrypt); //事件类型的url  {"EventType":"check_url"}
        $res = $crypt->getEncryptedMap("success"); //制造一个返回成功事件
        $data = json_decode($res,true);
        $text_arr = json_decode($text,true);
        logger('EventType'.json_encode($text_arr).';data：'.json_encode($text_arr));
        if ($text_arr['EventType'] == 'check_url'){
            //订阅事件
            logger('EventType'.$text_arr['EventType'].';data：'.json_encode($data));

        }elseif ($text_arr['EventType'] == 'attendance_check_record'){
            //员工打卡事件
            logger('EventType'.$text_arr['EventType'].';data：'.json_encode($text_arr));

        }else{
            //无法辨别的事件
            logger('EventType'.$text_arr['EventType'].';data：'.json_encode($data));
        }
        //推送事件统一返回成功
        return response()->json($data);

    }






}
