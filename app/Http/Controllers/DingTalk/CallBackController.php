<?php

namespace App\Http\Controllers\DingTalk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DingCallbackCrypto;

class CallBackController extends Controller
{
    //
    public function receive_dingtalk_event(Request $request){

        logger($request->get('encrypt','').';'.$request->fullUrl().';证明url参数可以获取到'.$request->get('signature',''));
        $encrypt = $request->get('encrypt','');
        $signature = $request->get('signature','');
        $timestamp = $request->get('timestamp','');
        $nonce = $request->get('nonce','');
        $msg_signature = $request->get('msg_signature','');
        $crypt = new DingCallbackCrypto("eXCjpvtXFjVEajtEmgFYhokNDtqbZUT7t954GDK", "o6W8zKoCnmyNvLpoL689xfgbzoz2r2k7V4FBaxAiSXY", "dingcibjfzwphqk9foye");
        $text = $crypt->getDecryptMsg($signature, $timestamp, $nonce, $encrypt);
        $res = $crypt->getEncryptedMap("success");

        // var_dump($res);
        $data = json_decode($res);
        // var_dump($text);
        logger('text'.json_encode($text).';data：'.json_encode($data));
        return response()->json($data);

    }




}
