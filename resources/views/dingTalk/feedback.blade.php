@extends('dingTalk.layouts.default')

@section('feedback', 'active')
@section('visit', '')
@section('manage_feedback', '')
@section('manage_visit', '')


@section('sidebar')
    @parent
@endsection



@section('modal')
    @parent
    <!--弹出的modal框-->
    <div id="modal_feeedback" style="text-align:center;display:none;">
        <form class="layui-form layui-form-pane" action="" lay-filter="feedback">
            <!--所属-->
            <div class="layui-form-item">
                <label class="layui-form-label">所属</label>
                <div class="layui-input-block">
                    <select name="blong" lay-filter="aihao">
                        <option value="请选择">请选择</option>

                        @forelse ($project_list as $project)
                            <option value="{{ $project->project_name }}">{{ $project->project_name }}</option>
                        @empty

                        @endforelse

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
            <!--姓名-->
            <div class="layui-form-item">
                <label class="layui-form-label">姓名</label>
                <div class="layui-input-inline">
                    <input type="text" name="name" lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>
            <?php $dingtalk_web_subassembly = env('dingtalk_web_subassembly',''); ?>
            <!--客户顾虑点-->
            <?php
                if (strpos($dingtalk_web_subassembly,'customer_concerns') !== false){
            ?>
                <div class="layui-form-item">
                    <label class="layui-form-label">客户顾虑点</label>
                    <div class="layui-input-inline">
                        <input type="text" name="customer_concerns" lay-verify="" autocomplete="off" class="layui-input">
                    </div>
                </div>
            <?php }else{ ?>
            <div class="layui-form-item" style="display: none;">
                <label class="layui-form-label">客户顾虑点</label>
                <div class="layui-input-inline">
                    <input type="text" name="customer_concerns" lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>

            <?php } ?>


            <!--是否近视-->
            <?php
            if (strpos($dingtalk_web_subassembly,'is_myopia') !== false){
                ?>
                <div class="layui-form-item">
                    <label class="layui-form-label">是否近视</label>
                    <div class="layui-input-inline">
                        <select name="is_myopia" lay-search="">
                            <option value="">请选择</option>

                            <option value="是">是</option>
                            <option value="否">否</option>

                        </select>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="layui-form-item" style="display: none;">
                    <label class="layui-form-label">是否近视</label>
                    <div class="layui-input-inline">
                        <select name="is_myopia" lay-search="">
                            <option value="">请选择</option>

                            <option value="是">是</option>
                            <option value="否">否</option>

                        </select>
                    </div>
                </div>

            <?php } ?>

                <!--资源平台-->
            <?php
            if (strpos($dingtalk_web_subassembly,'resource_platform') !== false){
                ?>
            <div class="layui-form-item">
                <label class="layui-form-label">资源平台</label>
                <div class="layui-input-inline">
                    <input type="text" name="resource_platform" lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>
            <?php }else{ ?>
            <div class="layui-form-item" style="display: none;">
                <label class="layui-form-label">资源平台</label>
                <div class="layui-input-inline">
                    <input type="text" name="resource_platform" lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>

            <?php } ?>

                <!--区域-->
            <?php
            if (strpos($dingtalk_web_subassembly,'region') !== false){
                ?>
            <div class="layui-form-item">
                <label class="layui-form-label">区域</label>
                <div class="layui-input-inline">
                    <input type="text" name="region" lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>
            <?php }else{ ?>
            <div class="layui-form-item" style="display: none;">
                <label class="layui-form-label">区域</label>
                <div class="layui-input-inline">
                    <input type="text" name="region" lay-verify="" autocomplete="off" class="layui-input">
                </div>
            </div>

            <?php } ?>



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

                        @forelse ($short_feedback_list as $short_feedback)
                            <option value="{{ $short_feedback->short_feeback }}">{{ $short_feedback->short_feeback }}</option>
                        @empty

                        @endforelse

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
        <button type="submit" class="layui-btn" id="sub_btn" data-subassembly_str="<?php echo $dingtalk_web_subassembly;?>" lay-submit="" lay-filter="demo1">立即提交</button>
    </div>

    <div id="modal_bind_ec" style="text-align:center;display:none;height: 210px;width: 400px;">
        <form class="layui-form layui-form-pane" action="" lay-filter="bindEc">
            <!--EC用户列表-->
            <div class="layui-form-item">
                <label class="layui-form-label">EC用户列表</label>
                <div class="layui-input-inline">
                    <select name="ec_user_list" lay-verify="required" lay-search="">
                        <option value="">请选择</option>

                        @forelse ($ec_user_list as $ec_user)
                            <option value="{{ $ec_user->userId }}">{{ $ec_user->deptName }}</option>
                        @empty

                        @endforelse

                    </select>
                </div>
            </div>

        </form>
        <!--操作按钮-->
        <button type="submit" class="layui-btn" id="sub_bind_btn" lay-submit="" lay-filter="demo1">立即绑定</button>
    </div>
@endsection




@section('content')
    <link href="{{ asset('dingTalk/investment/layui.css') }}" rel="stylesheet"/>
{{--    --}}{{--<script src="https://cdn.bootcdn.net/ajax/libs/layui/2.6.8/layui.min.js"></script>--}}
{{--    <script src="{{ asset('dingTalk/investment/layui.min.js') }}"></script>--}}

<div>
    <span id="show_ecuser_info"></span>
    <span id="show_ecuser_leave">; </span>
</div>

<table id="feedback" lay-filter="feedback"></table>
<script type="text/html" id="toolbar_header">
  <div class="layui-btn-container">
    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>
  </div>
</script>

<script>
layui.use('table', function(){
  let table = layui.table;
  let user_info = JSON.parse(localStorage.getItem("user_info"))
  let dngding_user_id = user_info.data.id //'5'
    let subassembly_str =$("#sub_btn").attr('data-subassembly_str');
    let cols_arr = [ //表头
        {field: 'id', title: 'ID', width:80 }
        // ,{field: 'dingding_user_id', title: '用户名'}
        ,{field: 'blong', title: '所属'}
        ,{field: 'data_date', title: '日期'}
        ,{field: 'name', title: '姓名'}
        ,{field: 'phone', title: '手机号'}
        ,{field: 'feedback_short', title: '反馈'}
        ,{field: 'feedback_detail', title: '跟进记录'}
    ];
    if (subassembly_str.indexOf('customer_concerns') != -1){
        //查询到了客户焦虑点
        cols_arr.push({field: 'customer_concerns', title: '客户顾虑点'})
    }
    if (subassembly_str.indexOf('is_myopia') != -1){
        //查询到了 是否近视
        cols_arr.push({field: 'is_myopia', title: '是否近视'})
    }
    if (subassembly_str.indexOf('resource_platform') != -1){
        //查询到了 资源平台
        cols_arr.push({field: 'resource_platform', title: '资源平台'})
    }
    if (subassembly_str.indexOf('region') != -1){
        //查询到了 区域
        cols_arr.push({field: 'region', title: '区域'})
    }
    cols_arr.push({fixed: 'right', width:200, align:'center', toolbar: '#bar_feedback'})//这里的toolbar值是模板元素的选择器





  //第一个实例
  table.render({
    elem: '#feedback'
    // ,toolbar:true
    // ,height: 1080
    // ,width: 1080
    ,toolbar:'#toolbar_header'
    ,url: '/get_list' //数据接口
    ,where: {user_id: dngding_user_id} //如果无需传递额外参数，可不加该参数
    ,method: 'post' //如果无需自定义HTTP类型，可不加该参数
  //request: {} //如果无需自定义请求参数，可不加该参数
  //response: {} //如果无需自定义数据响应名称，可不加该参数
    ,page: true //开启分页
    ,cols: [cols_arr],
      defaultToolbar: ['filter', 'exports', {
          title: '提示' //标题
          ,layEvent: 'bind_ec_info' //点击弹出绑定modal框，用于 toolbar 事件中使用
          ,icon: 'layui-icon-tips' //图标类名
      }]
  });

   //监听工具条
    table.on('tool(feedback)', function(obj){ //注：tool 是工具条事件名，feedback 是 table 原始容器的属性 lay-filter="对应的值"
      var data = obj.data; //获得当前行数据
      var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
      var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

      if(layEvent === 'detail'){ //查看
        //do somehing
        console.log('detail')
        modal_data_func(layEvent,data)
        open_modal(1,'展示','modal_feeedback')

      }  else if(layEvent === 'edit'){ //编辑
        //do something
        console.log('edit')
        modal_data_func(layEvent,data)
        open_modal(1,'更新','modal_feeedback')
        //方法更新
        //同步更新缓存对应的值
        // obj.update({
        //   username: '123'
        //   ,title: 'xxx'
        // });

      } else if(layEvent === 'del'){ //删除
        // layer.confirm('真的删除行么', function(index){
        //   obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
        //   layer.close(index);
        //   //向服务端发送删除指令
        // });
      } else {
        layer.alert('未知操作');
      }
    });

    //监听头部工具条
    table.on('toolbar(feedback)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
        switch(obj.event){
            case 'add':
                console.log('add')
                modal_data_func(obj.event,[])
                open_modal(1,'新增','modal_feeedback')
                break;
            case 'bind_ec_info':
                console.log('bind_ec_info')
                let user_info = JSON.parse(localStorage.getItem("user_info"))
                if((user_info.is_bind_ec).length == 0){ // "",[]
                    console.log("为空");
                    //弹出modal
                    modal_ec_bind()
                    open_modal(1,'绑定EC用户','modal_bind_ec')
                }else{
                    console.log("不为空");
                    //不为空，则已绑定过，禁止绑定
                    alert('EC关系已绑定，联系管理员');
                }
        };
    });

    //搜索功能

    // table.reload('feedback', {
    //     where: { //设定异步数据接口的额外参数，任意设
    //         aaaaaa: 'xxx'
    //         ,bbb: 'yyy'
    //     }
    //     ,page: {
    //         curr: 1 //重新从第 1 页开始
    //     }
    // }); //只重载数据



});
//EC用户绑定的前置方法
function modal_ec_bind(){
    $("#sub_bind_btn").unbind("click")

    layui.$('#sub_bind_btn').on('click', function(){
        console.log('#sub_bind_btn')
        layui.use(['form'], function(){
            let form = layui.form
                ,layer = layui.layer
            let data = form.val("bindEc");
            console.log(data)
            let user_info = JSON.parse(localStorage.getItem("user_info"))
            let dingding_userid = user_info.data.userid;
            // alert(JSON.stringify(data));
            //这边进行请求方法，更新与增加的集合
            $.ajax({
                //请求方式
                type : "POST",
                //请求的媒体类型
                contentType: "application/json;charset=UTF-8",
                //请求地址
                url : "/bing_ec_user",
                //数据，json字符串
                data : JSON.stringify({"dingding_userid":dingding_userid,"ec_user_id":data.ec_user_list}),//JSON.stringify(list),
                //请求成功
                success : function(result) {
                    console.log(result);
                    //清除用户信息记录然后更新新的
                    alert(result.msg);
                    if(result.code == '2'){
                        //数据并未绑定不需要清理用户数据，不需要刷新
                    }else{
                        localStorage.clear()
                        location.reload();
                    }

                },
                //请求失败，包含具体的错误信息
                error : function(e){
                    layer.msg('请重新操作');
                    console.log(e.status);
                    console.log(e.responseText);
                    location.reload();
                }
            });
        }); //layui.use

    });//layui的click的事件的结束
}

function open_modal(type,title='信息',content='modal_feeedback'){
        layer.open({
          type: type,
          title:title,
          content: $('#'+content+'') //这里content是一个普通的String
        });
}
function modal_data_func(layEvent,data){
    console.log(layEvent);
    console.log(data);
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
        ,layer = layui.layer
        ,layedit = layui.layedit
        ,laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#data_date'
            ,value: new Date()
            ,isInitValue: true //是否允许填充初始值，默认为 true
        });
    //先做判断类型，进行添加值
    let assignment = {}

    switch (layEvent) {
    case 'edit':
        assignment.blong = data.blong;
        assignment.data_date = data.data_date;
        assignment.name = data.name;
        assignment.customer_concerns = data.customer_concerns;
        assignment.phone = data.phone;
        assignment.feedback_short = data.feedback_short;
        assignment.feedback_detail = data.feedback_detail;
        assignment.is_myopia = data.is_myopia;
        assignment.resource_platform = data.resource_platform;
        assignment.region = data.region;
        $("#sub_btn").html('修改');
        $("#sub_btn").show();
        get_opera_data('edit',data,data.dingding_user_id) //操作的方法的集合
        break;
    case 'detail':
        assignment.blong = data.blong;
        assignment.data_date = data.data_date;
        assignment.name = data.name;
        assignment.customer_concerns = data.customer_concerns;
        assignment.phone = data.phone;
        assignment.feedback_short = data.feedback_short;
        assignment.feedback_detail = data.feedback_detail;
        assignment.is_myopia = data.is_myopia;
        assignment.resource_platform = data.resource_platform;
        assignment.region = data.region;
        //隐藏掉提交按钮
        $("#sub_btn").hide();
         break;
    case 'add':
        //为所属进行赋值
        assignment.blong = '';
        let user_info = JSON.parse(localStorage.getItem("user_info"))
        let show_user_name = user_info.data.department_name+user_info.data.position + '--' +user_info.data.name

        let project_arr = <?php echo json_encode($project_list);?>;
        console.log(project_arr)
        for (x in project_arr) {
            console.log(project_arr[x].project_name)
            if(show_user_name.indexOf(project_arr[x].project_name) !== -1){
                //包含此項目名稱
                assignment.blong = project_arr[x].project_name;
            }else{
                //跳過
                continue;
            }
        }
        let dingding_user_id = user_info.data.id;

        assignment.name = '';
        assignment.phone = '';
        assignment.feedback_short = '';
        assignment.feedback_detail = '';
        assignment.is_myopia = '';
        assignment.resource_platform = '';
        assignment.region = '';

        $("#sub_btn").html('新增');
        $("#sub_btn").show();
        get_opera_data('add',data,dingding_user_id) //操作的方法的集合
        break;
    }

    console.log(assignment);
    //表单赋值
    form.val('feedback', assignment)

    //获取表单值并请求后台
    function get_opera_data(opera_type,originally_data,user_id){

        $("#sub_btn").unbind("click")

        layui.$('#sub_btn').on('click', function(){
            var data = form.val("feedback");
            // alert(JSON.stringify(data));
            //这边进行请求方法，更新与增加的集合
            $.ajax({
                //请求方式
                type : "POST",
                //请求的媒体类型
                contentType: "application/json;charset=UTF-8",
                //请求地址
                url : "/opera_data",
                //数据，json字符串
                data : JSON.stringify({"opera_type":opera_type,"originally_data":originally_data,"dingding_user_id":user_id,"latest_data":data}),//JSON.stringify(list),
                //请求成功
                success : function(result) {
                    console.log(result);
                    layer.msg(result.msg);
                    location.reload();

                },
                //请求失败，包含具体的错误信息
                error : function(e){
                    layer.msg('请重新操作');
                    console.log(e.status);
                    console.log(e.responseText);
                    location.reload();
                }
            });

        });//layui的click的事件的结束
    }


});
}


function get_ecuser_leave_info(ec_userid){
    console.log('get_ecuser_leave_info')
    $.ajax({
        //请求方式
        type : "POST",
        //请求的媒体类型
        contentType: "application/json;charset=UTF-8",
        //请求地址
        url : "/get_ec_user_leave_info",
        //数据，json字符串
        data : JSON.stringify({"ec_userid":ec_userid}),//JSON.stringify(list),
        //请求成功
        success : function(result) {
            console.log(result);
            if(result.code == '1'){
                //工作中
                $("#show_ecuser_leave").html("<span style='font-size: 17px;color: #40af26;'>接资源中</span>");
            }else if(result.code == '0'){
                //请假中
                $("#show_ecuser_leave").html("<span style='font-size: 17px;color: #e2102b;'>停资源中</span>");

            }else if(result.code == '2'){
                //未配置分配权限
                $("#show_ecuser_leave").html("<span style='font-size: 17px;color: #d0cc2a;'>未配置分配权限</span>");
            }else{
                //异常
                $("#show_ecuser_leave").html("<span style='font-size: 17px;color: #2a35d0;'>异常</span>");
            }
        },
        //请求失败，包含具体的错误信息
        error : function(e){
            console.log(e.status);
            console.log(e.responseText);
        }
    });
}

//页面加载完毕，进行赋值的展示等
$(function(){
    console.log("页面加载完成！");
    let user_info = JSON.parse(localStorage.getItem("user_info"))
    if((user_info.is_bind_ec).length == 0){ // "",[]
        console.log('未绑定，提示去绑定')
        $("#show_ecuser_info").html("EC关系未绑定");//
    }else{
        console.log('已绑定，去查询数据和展示')
        //1.将用户名放进#show_ecuser_info中
        console.log(user_info.is_bind_ec)
        $("#show_ecuser_info").html("已绑定EC信息："+user_info.is_bind_ec.deptName);
        //2.从后台获取是否请假
        get_ecuser_leave_info(user_info.is_bind_ec.ec_userid)
    }

});

</script>
<script type="text/html" id="bar_feedback">

  <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>

</script>



@endsection
