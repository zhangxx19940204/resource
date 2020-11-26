<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::any('/receive_fei_oceanengine', [Oceanengine\FeiyuController::class, 'receive_fei_oceanengine']); //接收头条飞鱼crm发送的数据
Route::any('/get_fast_horse_data', [FastHorse\FasthorseController::class, 'get_fast_horse']); //获取快马账号的数据 （定时）
Route::any('/get_global_join', [GlobalJoin\GlobaljoinController::class, 'receive_global_join']); //接收全球加盟网账号的数据
Route::any('/get_second_third', [ChannelNetwork\SecondthirdController::class, 'get_second_third']); //获取23网的数据 (定时)

//邮件系统
Route::any('/get_mail_list', [PromoteData\PromoteDataController::class, 'get_mail_list']);//获取用户的邮件列表（外部设定的定时访问）


//EC的用户的相关操作
Route::any('/get_framework_info', [EC\UserController::class, 'get_framework_info']);//更新EC用户信息
Route::any('/get_user_list', [EC\UserController::class, 'get_framework_info']);//更新EC用户信息

