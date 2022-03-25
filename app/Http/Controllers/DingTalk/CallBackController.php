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
        $crypt = new DingCallbackCrypto("eXCjpvtXFjVEajtEmgFYhokNDtqbZUT7t954GDK", "o6W8zKoCnmyNvLpoL689xfgbzoz2r2k7V4FBaxAiSXY", "ding72571b91c47e745235c2f4657eb6378f");
        $text = $crypt->getDecryptMsg($signature, $timestamp, $nonce, $encrypt);
        $res = $crypt->getEncryptedMap("success");

        var_dump($res);
        $data = json_decode($res);
        var_dump($text);
        logger('text'.$text.';res'.$res.';data'.$data);
        return response()->json(["msg_signature"=>"111108bb8e6dbce3c9671d6fdb69d1506xxxx","timeStamp"=>"1783610513","nonce"=>"123456","encrypt"=>"1ojQf0NSvw2WPvW7LijxS8UvISr8pdDP+rXpPbcLGOmIxxxx"]);


    }




}
