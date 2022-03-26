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

        // logger('事件推送的url'.$request->fullUrl());
        $encrypt = $request->get('encrypt','');
        $signature = $request->get('signature','');
        $timestamp = $request->get('timestamp','');
        $nonce = $request->get('nonce','');
        $msg_signature = $request->get('msg_signature','');
        $crypt = new DingCallbackCrypto("eXCjpvtXFjVEajtEmgFYhokNDtqbZUT7t954GDK", "o6W8zKoCnmyNvLpoL689xfgbzoz2r2k7V4FBaxAiSXY", "dingcibjfzwphqk9foye");
        $res = $crypt->getEncryptedMap("success"); //制造一个返回成功事件
        $data = json_decode($res,true);
        if(empty($encrypt) || empty($signature)){
            //参数获取不全时
            return response()->json($data);
        }

        $text = $crypt->getDecryptMsg($signature, $timestamp, $nonce, $encrypt); //事件类型的url  {"EventType":"check_url"}
        $text_arr = json_decode($text,true);
        logger(';data：'.json_encode($text_arr));
        if ($text_arr['EventType'] == 'check_url'){
            //订阅事件
        }elseif ($text_arr['EventType'] == 'attendance_check_record'){
            //员工打卡事件
            $this->deal_check_record($text_arr['EventType'],$text_arr['DataList'][0]);
        }else{
            //无法辨别的事件
        }
        //推送事件统一返回成功
        return response()->json($data);

    }

    //打卡记录处理
    public function deal_check_record($EventType,$dataArr){
        try {

            if (!array_key_exists('userId',$dataArr)){
                //没有userid
                return;
            }
            $locationMethod = array_key_exists('locationMethod', $dataArr)?$dataArr['locationMethod']:'';
            $locationResult = array_key_exists('locationResult', $dataArr)?$dataArr['locationResult']:'';
            $checkTime = array_key_exists('checkTime', $dataArr)?$dataArr['checkTime']:'';
            $check_date = date('Y-m-d H:i:s',substr($checkTime,0,10));
            DB::table('dingding_user_checkrecord')
                ->updateOrInsert(
                    ['event_type' => $EventType, 'user_id' => $dataArr['userId']],
                    ['data' => json_encode($dataArr),'locationMethod'=>$locationMethod,'checkTime'=>$check_date,'locationResult'=>$locationResult]
                );
            return;
        } catch (Exception $e) {

            return;
        }

        return;

    }






}
