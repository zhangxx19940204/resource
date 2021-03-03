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

  @section('modal')
  @show


</html>
