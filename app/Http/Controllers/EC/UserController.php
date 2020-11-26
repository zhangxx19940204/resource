<?php

namespace App\Http\Controllers\EC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    //
    public function get_framework_info(Request $request){
        $url = 'https://open.workec.com/v2/org/struct/info';
        $cid = '10569552';
        $appId = '644478092940673024';
        $appSecret = 'twtMv7TQBBdja7PKMIp';
        $res_data_json = $this->http_get($url, $cid, $appId, $appSecret);
        $res_data = json_decode($res_data_json,true);
//        echo '<pre>';
//        var_dump($res_data);
//        echo '</pre>';
//        die();
        DB::beginTransaction();
        try {
            DB::table('ec_users')->truncate();
            DB::table('ec_depts')->truncate();
            DB::table('ec_users')->insert($res_data['data']['users']);
            DB::table('ec_depts')->insert($res_data['data']['depts']);
            DB::commit();
            logger('数据库更新或者操作成功');

        } catch (Exception $e ) {
            logger('数据库更新或者操作失败');
            DB::rollBack();
        }


    }
}
