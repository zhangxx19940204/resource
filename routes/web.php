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
Route::any('/get_global_join', [GlobalJoin\GlobaljoinController::class, 'get_global_join']); //获取全球加盟网账号的数据（定时）
Route::any('/get_zhihu_data', [ZhiHu\ZhiHuController::class, 'get_zhihu_data']); //获取知乎账号的数据（定时）

Route::any('/get_fast_horse_data', [FastHorse\FasthorseController::class, 'get_fast_horse']); //获取快马账号的数据 （定时）
Route::any('/get_second_third', [ChannelNetwork\SecondthirdController::class, 'get_second_third']); //获取23网的数据 (定时)
Route::any('/get_5988_data', [ZuoSai\ZuoSaiController::class, 'get_5988_data']); //获取5988的数据 (需要定时)

//邮件系统
Route::any('/get_mail_list', [PromoteData\PromoteDataController::class, 'get_mail_list']);//获取用户的邮件列表（外部设定的定时访问）
Route::any('/synchronous_maildata', [PromoteData\PromoteDataController::class, 'synchronous_mailData']);//同步邮件数据到统计系统中


//EC的用户的相关操作
Route::any('/synchronous_feedback', [EC\UserController::class, 'synchronous_single_feedback']);//更新单个EC客户的反馈(EC客户端用户主动调用)
//根据用户的反馈内容，来添加类别
Route::any('/add_feedbackContent_short', [EC\UserController::class, 'add_feedbackContent_short']);//

Route::any('/get_framework_info', [EC\UserController::class, 'get_framework_info']);//更新EC用户信息和部门信息 （需要单独调用去更新新用户）
Route::any('/add_deptName', [EC\UserController::class, 'add_deptName']);//更新EC用户的所有部门名


Route::any('/distribute_ec_data', [DistributeEc\DistributeDataController::class, 'distribute_ec_data']);//自动下发资源模块



//同步统计系统中，那些报错数据的相关人员ID
Route::any('/synchronous_failureCause_userId', [EC\UserController::class, 'synchronous_failureCause_userId']);

//对接53客服的消息
Route::any('/receive_53kf_info', [CustomerService\FiveThreeController::class, 'receive_53kf_info']);//接收53客服的所有消息类型
Route::any('/receive_53kf_user_info', [CustomerService\FiveThreeController::class, 'receive_53kf_user_info']);//接收53客服的客户消息


//钉钉微应用 ==招商资源中心
Route::any('/user_login', [DingTalk\FeedbackController::class, 'user_login']); //接收code，查询数据

Route::get('/investment', [DingTalk\FeedbackController::class, 'index']); //默认为资源反馈表
Route::any('/get_list', [DingTalk\FeedbackController::class, 'get_list']); //资源反馈表的列表
Route::any('/opera_data', [DingTalk\FeedbackController::class, 'opera_data']); //资源反馈表的操作


Route::get('/visit', [DingTalk\VisitController::class, 'index']); //来访进款表
Route::any('/get_visit_list', [DingTalk\VisitController::class, 'get_list']); //资源反馈表的列表
Route::any('/opera_visit_data', [DingTalk\VisitController::class, 'opera_data']); //资源反馈表的操作

