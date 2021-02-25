<!doctype html>

<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>云端办公</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <meta name="msapplication-TileColor" content="#206bc4"/>
    <meta name="theme-color" content="#206bc4"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="robots" content="noindex,nofollow,noarchive"/>
    <link rel="icon" href="./favicon.ico" type="image/x-icon"/>
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon"/>
    <!-- CSS files -->
    <link href="{{ asset('dingTalk/investment/tabler.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('dingTalk/investment/demo.min.css') }}" rel="stylesheet"/>

    <script src="https://cdn.staticfile.org/jquery/3.5.0/jquery.min.js"></script>

    <!-- Libs JS -->
    <script src="{{ asset('dingTalk/investment/bootstrap.bundle.min.js') }}"></script>
    <!-- Tabler Core -->
    <script src="{{ asset('dingTalk/investment/tabler.min.js') }}"></script>

    <script src="https://g.alicdn.com/dingding/dingtalk-jsapi/2.10.3/dingtalk.open.js"></script>




    <style>
      body {
      	display: none;
      }
    </style>
  </head>
  <body class="antialiased">
    <div class="page">


      <div class="content"> <!--是否紧靠上边-->
        <div class="container-xl"> <!--是否紧靠两边-->

            <!-- 头部代码开始 -->

            <div class="mb-3">

              <header class="navbar navbar-expand-md navbar-dark">
                <div class="container-xl">
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  <!-- <a href="./#" class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pr-0 pr-md-3"> -->
                    <img src="{{ asset('dingTalk/investment/logo.png') }}" alt="Tabler" class="navbar-brand-image">
                  <!-- </a> -->
                  <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item dropdown d-none d-md-flex mr-3">
                      <!-- pc端的名字 -->
                      <span id="pc_user_name"></span>
                    </div>
                    <!-- 头像以及下拉框 -->
                    <div class="nav-item dropdown">
                      <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-toggle="dropdown">
                        <!-- 头像 -->
                        <span class="avatar" id="user_avatar" style=""></span>

                      </a>
                      <div class="dropdown-menu dropdown-menu-right">
                        <!-- 头像上的下拉框 -->
                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="#">
                          <svg xmlns="http://www.w3.org/2000/svg" class="icon dropdown-item-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"/><path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" /><path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" /><line x1="16" y1="5" x2="19" y2="8" /></svg>
                            <!-- 移动端的名字 -->
                            <span id="mobile_user_name"></span>
                        </a>


                      </div>
                    </div>
                  </div>
                </div>
              </header>

              <!-- 头部结束，下部内容开始 -->
              <div class="navbar-expand-md">
                <div class="collapse navbar-collapse" id="navbar-menu">
                  <div class="navbar navbar-light">
                    <div class="container-xl">


                        <!-- tab切换的代码-->
                        @section('sidebar')
                        <ul class="navbar-nav">

                            <li class="nav-item @yield('feedback')">
                              <a class="nav-link" href="/investment" >

                                <span class="nav-link-title">
                                  资源反馈表
                                </span>

                              </a>
                            </li>

                            <li class="nav-item @yield('visit')">
                              <a class="nav-link" href="/visit" >

                                <span class="nav-link-title">
                                  来访进款表
                                </span>

                                <!-- <span class="badge bg-red">2</span> -->

                              </a>
                            </li>

                        </ul>
                        @show

                    </div>
                  </div>
                </div>
              </div>
              <!-- 下部结束 -->

            </div>

            <!-- 头部代码结束 -->

            <!-- 自由内容的开始 -->

             @yield('content')
            <!-- 自由内容的结束 -->

        </div>
      </div>
    </div>


    <script>
        document.body.style.display = "block"
        // localStorage.clear()
        // if(window.localStorage){
        //     alert("浏览支持localStorage")

        // }else{
        //     alert("浏览暂不支持localStorage")

        // }

        //判断localstorage是否存在；是否过期；
        if (localStorage.getItem("user_info") != null) {
            //用户信息存在，判断是否时间已到
            if((new Date().getTime()) >= localStorage.getItem("expire_time")){
                get_user_info_and_set();
            }else{
                //过期时间未到，不用操作
            }

    	}else{
            //用户信息不存在，请求并添加到localstorage中，并设定时间
            get_user_info_and_set();
    	}



        $(document).ready(function(){
            user_info = JSON.parse(localStorage.getItem("user_info"))

            // var a = JSON.stringify($user_info.data);
            // alert(a);
            let show_user_name = user_info.data.department_name+user_info.data.position + '--' +user_info.data.name
            $('#pc_user_name').text(show_user_name);
            $('#mobile_user_name').text(show_user_name);
            $("#user_avatar").css({"background-image":"url("+user_info.data.avatar+")"});

        });

        function get_user_info_and_set() {
            dd.ready(function() {
                // dd.ready参数为回调函数，在环境准备就绪时触发，jsapi的调用需要保证在该回调函数触发后调用，否则无效。
                dd.runtime.permission.requestAuthCode({
                    corpId: "{{ $corp_id }}",
                    onSuccess: function(result) {
                        //
                        $.ajax({
                            url: '/user_login',
                            type:"POST",
                            data: {"event":"get_userinfo","code":result.code},
                            dataType:'json',
                            timeout: 9000,
                            success: function (data, status, xhr) {
                                // var a = JSON.stringify(data);
                                //  $('#test_content').text(a);
                                var info = data;
                                if(typeof data == 'string'){
                                     info = JSON.parse(data);
                                }
                                localStorage.setItem("user_info", JSON.stringify(info))
                                localStorage.setItem("expire_time", (new Date().getTime())+72000000)

                                if(localStorage.hasOwnProperty("user_info")){
                                    //True
                                    alert("登录成功");
                                    window.location.reload()

                                }else{
                                    alert("登录失败，请重试");
                                }




                            },
                            error: function (xhr, errorType, error) {
                                logger.e(errorType + ', ' + error);
                                $('#error_info').text(errorType + ', ' + error);
                                alert('异常，重新登录');
                            }
                        });
                        //
                    },
                    onFail : function(err) {}

                });
            });
        }


    </script>
  </body>

  <!--弹出的modal框-->
<div id="modal_feeedback" style="text-align:center;display:none;">
<form class="layui-form layui-form-pane" action="" lay-filter="feedback">
    <!--所属-->
  <div class="layui-form-item">
    <label class="layui-form-label">所属</label>
    <div class="layui-input-block">
      <select name="blong" lay-filter="aihao">
        <option value="请选择">请选择</option>
        <option value="腩潮鲜">腩潮鲜</option>
        <option value="原时烤肉">原时烤肉</option>
        <option value="半城外">半城外</option>
        <option value="下江腩">下江腩</option>
        <option value="阿城牛货">阿城牛货</option>
        <option value="隐匠">隐匠</option>
      </select>
    </div>
  </div>
  <!--日期-->
  <div class="layui-form-item">
      <label class="layui-form-label">日期</label>
      <div class="layui-input-inline">
        <input type="text" name="data_date" id="data_date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--手机-->
  <div class="layui-form-item">
      <label class="layui-form-label">手机</label>
      <div class="layui-input-inline">
        <input type="tel" name="phone" lay-verify="required|phone" autocomplete="off" class="layui-input">
      </div>
  </div>
<!--反馈-->
    <div class="layui-form-item">
      <label class="layui-form-label">反馈</label>
      <div class="layui-input-inline">
        <select name="feedback_short" lay-verify="required" lay-search="">
            <option value="">请选择</option>
            <!--<option value="简短反馈">简短反馈</option>-->
            <option value="意向客户">意向客户</option>
            <option value="正常咨询">正常咨询</option>
            <option value="已加微信发资料">已加微信发资料</option>
            <option value="在忙，加微信">在忙，加微信</option>
            <option value="预约回电">预约回电</option>
            <option value="多次未接（3次及以上）">多次未接（3次及以上）</option>
            <option value="未接">未接</option>
            <option value="接了就挂">接了就挂</option>
            <option value="未咨询，被黑">未咨询，被黑</option>
            <option value="关机">关机</option>
            <option value="停机">停机</option>
            <option value="空号">空号</option>
            <option value="没钱，费用接受不了">没钱，费用接受不了</option>
            <option value="公海资源">公海资源</option>
            <!--<option value="重复他人">重复他人</option>-->
            <option value="无意向">无意向</option>
            <option value="同行">同行</option>
            <option value="学技术买设备">学技术买设备</option>
            <option value="推广推销">推广推销</option>
        </select>
      </div>
    </div>
    <!--跟进记录-->
  <div class="layui-form-item layui-form-text">
    <label class="layui-form-label">跟进记录</label>
    <div class="layui-input-block">
      <textarea placeholder="跟进记录" name="feedback_detail" class="layui-textarea"></textarea>
    </div>
  </div>

 </form>
 <!--操作按钮-->
 <button type="submit" class="layui-btn" id="sub_btn" lay-submit="" lay-filter="demo1">立即提交</button>
</div>



<!--客户来访表的modal框-->
  <!--弹出的modal框-->
<div id="modal_visit" style="text-align:center;display:none;">
<form class="layui-form layui-form-pane" action="" lay-filter="visit">
    <!--所属-->
  <div class="layui-form-item">
    <label class="layui-form-label">所属</label>
    <div class="layui-input-block">
      <select name="blong" lay-filter="aihao">
        <option value="请选择">请选择</option>
        <option value="腩潮鲜">腩潮鲜</option>
        <option value="原时烤肉">原时烤肉</option>
        <option value="半城外">半城外</option>
        <option value="下江腩">下江腩</option>
        <option value="阿城牛货">阿城牛货</option>
        <option value="隐匠">隐匠</option>

      </select>
    </div>
  </div>
  <!--月份-->
  <div class="layui-form-item">
      <label class="layui-form-label">月份</label>
      <div class="layui-input-inline">
        <input type="text" name="visit_month" id="visit_month" lay-verify="date" placeholder="yyyy-MM" autocomplete="off" class="layui-input">
      </div>
  </div>

    <!--来访日期-->
  <div class="layui-form-item">
      <label class="layui-form-label">来访/合作 日期</label>
      <div class="layui-input-inline">
        <input type="text" name="visit_date" id="visit_date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--品牌-->
  <div class="layui-form-item">
      <label class="layui-form-label">品牌</label>
      <div class="layui-input-inline">
        <input type="text" name="visit_brand" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--客户姓名-->
  <div class="layui-form-item">
      <label class="layui-form-label">客户姓名</label>
      <div class="layui-input-inline">
        <input type="text" name="visit_name" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
   <!--性别-->
  <div class="layui-form-item">
      <label class="layui-form-label">性别</label>
      <div class="layui-input-block">
        <select name="visit_sex" lay-filter="aihao">
            <option value="请选择">请选择</option>
            <option value="男">男</option>
            <option value="女">女</option>

        </select>
        <!--<input type="text" name="visit_sex" lay-verify="required" autocomplete="off" class="layui-input">-->
      </div>
  </div>
   <!--来访结果-->
  <div class="layui-form-item">
      <label class="layui-form-label">来访结果</label>
      <div class="layui-input-block">

        <select name="visit_result" lay-filter="aihao">
            <option value="请选择">请选择</option>
            <option value="已签约">已签约</option>
            <option value="未签">未签</option>

        </select>
        <!--<input type="text" name="visit_result" lay-verify="required" autocomplete="off" class="layui-input">-->
      </div>
  </div>
   <!--进款分类-->
  <div class="layui-form-item">
      <label class="layui-form-label">进款分类</label>
      <div class="layui-input-block">
          <select name="money_type" lay-filter="aihao">
            <option value="请选择">请选择</option>
            <option value="定金">定金</option>
            <option value="异地定金">异地定金</option>
            <option value="尾款">尾款</option>
            <option value="全款">全款</option>
            <option value="无">无</option>

          </select>
        <!--<input type="text" name="money_type" lay-verify="required" autocomplete="off" class="layui-input">-->
      </div>
  </div>
    <!--入款-->
  <div class="layui-form-item">
      <label class="layui-form-label">入款</label>
      <div class="layui-input-inline">
        <input type="text" name="money_enter" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--待收尾款-->
  <div class="layui-form-item">
      <label class="layui-form-label">待收尾款</label>
      <div class="layui-input-inline">
        <input type="text" name="pending_closing" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--店型-->
  <div class="layui-form-item">
      <label class="layui-form-label">店型</label>
      <div class="layui-input-inline">
        <input type="text" name="shop_type" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
    <!--邀约人-->
  <div class="layui-form-item">
      <label class="layui-form-label">邀约人</label>
      <div class="layui-input-inline">
        <input type="text" name="invitee" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--谈判经理-->
  <div class="layui-form-item">
      <label class="layui-form-label">谈判经理</label>
      <div class="layui-input-inline">
        <input type="text" name="negotiation_manager" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--部门-->
  <div class="layui-form-item">
      <label class="layui-form-label">部门</label>
      <div class="layui-input-block">
           <select name="department" lay-filter="aihao">
            <option value="请选择">请选择</option>
            <option value="一部">一部</option>
            <option value="二部">二部</option>
            <option value="三部">三部</option>
            <option value="四部">四部</option>
            <option value="五部">五部</option>
            <option value="六部">六部</option>
            <option value="七部">七部</option>

          </select>
        <!--<input type="text" name="department" lay-verify="required" autocomplete="off" class="layui-input">-->
      </div>
  </div>
  <!--资源平台-->
  <div class="layui-form-item">
      <label class="layui-form-label">资源平台</label>
      <div class="layui-input-inline">
        <input type="text" name="resource_platform" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>

  <!--录入时间-->
  <div class="layui-form-item">
      <label class="layui-form-label">录入时间</label>
      <div class="layui-input-inline">
        <input type="text" name="include_time" id="include_time" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>

  <!--手机-->
  <div class="layui-form-item">
      <label class="layui-form-label">手机</label>
      <div class="layui-input-inline">
        <input type="tel" name="phone" lay-verify="required|phone" autocomplete="off" class="layui-input">
      </div>
  </div>

    <!--地址-->
  <div class="layui-form-item">
      <label class="layui-form-label">地址</label>
      <div class="layui-input-inline">
        <input type="text" name="address" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--年龄-->
  <div class="layui-form-item">
      <label class="layui-form-label">年龄</label>
      <div class="layui-input-inline">
        <input type="text" name="age" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--职业-->
  <div class="layui-form-item">
      <label class="layui-form-label">职业</label>
      <div class="layui-input-inline">
        <input type="text" name="occupational" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
    <!--未签原因-->
  <div class="layui-form-item">
      <label class="layui-form-label">未签原因</label>
      <div class="layui-input-inline">
        <input type="text" name="reason_not_signed" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--是否有合伙人-->
  <div class="layui-form-item">
      <label class="layui-form-label">是否有合伙人</label>
      <div class="layui-input-inline">
        <input type="text" name="is_partner" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--来访周期（距离拿资源时间）-->
  <div class="layui-form-item">
      <label class="layui-form-label">来访周期（距离拿资源时间）</label>
      <div class="layui-input-inline">
        <input type="text" name="visit_cycle" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>
  <!--签约周期-->
  <div class="layui-form-item">
      <label class="layui-form-label">签约周期</label>
      <div class="layui-input-inline">
        <input type="text" name="signing_cycle" lay-verify="required" autocomplete="off" class="layui-input">
      </div>
  </div>

 </form>
 <!--操作按钮-->
 <button type="submit" class="layui-btn" id="visit_sub_btn" lay-submit="" lay-filter="demo2">立即提交</button>
</div>

</html>
