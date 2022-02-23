@extends('dingTalk.layouts.default')

@section('feedback', '')
@section('visit', '')
@section('manage_feedback', 'active')
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
        <button type="submit" class="layui-btn" id="sub_btn" lay-submit="" lay-filter="demo1">立即提交</button>
    </div>

@endsection




@section('content')
    <link href="{{ asset('dingTalk/investment/layui.css') }}" rel="stylesheet"/>
    <script src="https://cdn.bootcdn.net/ajax/libs/layui/2.6.8/layui.min.js"></script>

    <form class="layui-form" lay-filter="filter_feedback"> <!-- 提示：如果你不想用form，你可以换成div等任何一个普通元素 -->

        <div class="layui-form-item">
            <label class="layui-form-label">所属</label>
            <div class="layui-input-block">
                <select name="filter_blong" lay-filter="aihao">
                    <option value="">请选择</option>

                    @forelse ($project_list as $project)
                        <option value="{{ $project->project_name }}">{{ $project->project_name }}</option>
                    @empty

                    @endforelse
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">日期</label>
            <div class="layui-input-block">
                <input type="text" name="filter_date" placeholder="日期" id="filter_date" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">手机号</label>
            <div class="layui-input-block">
                <input type="text" lay-verify="" name="filter_phone" placeholder="手机号" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">反馈</label>
            <div class="layui-input-block">
                <select name="filter_short" lay-filter="aihao">
                    <option value="">请选择</option>

                    @forelse ($short_feedback_list as $short_feedback)
                        <option value="{{ $short_feedback->short_feeback }}">{{ $short_feedback->short_feeback }}</option>
                    @empty

                    @endforelse
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">资源所属人</label>
            <div class="layui-input-block">
                <select name="filter_dingding_user" lay-filter="aihao">
                    <option value="">请选择</option>

                    @forelse ($filter_user_list as $single_user)
                        <option value="{{ $single_user->id }}">{{ $single_user->name }}</option>
                    @empty

                    @endforelse
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="*">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>

    </form>
<table id="feedback" lay-filter="feedback"></table>
<script type="text/html" id="toolbar_header">
  <div class="layui-btn-container">
{{--    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>--}}
  </div>
</script>

<script>
layui.use(['table','form','laydate',], function(){
  let table = layui.table;
  let user_info = JSON.parse(localStorage.getItem("user_info"))
  let dngding_user_id = user_info.data.id //'5'
  //第一个实例
  table.render({
    elem: '#feedback'
    // ,toolbar:true
    // ,height: 1080
    // ,width: 1080
    ,toolbar:'#toolbar_header'
    ,url: '/get_manage_feedback_list' //数据接口
    ,where: {user_id: dngding_user_id} //如果无需传递额外参数，可不加该参数
    ,method: 'post' //如果无需自定义HTTP类型，可不加该参数
  //request: {} //如果无需自定义请求参数，可不加该参数
  //response: {} //如果无需自定义数据响应名称，可不加该参数
    ,page: true //开启分页
    ,cols: [[ //表头
         {field: 'id', title: 'ID', width:80 }
        // ,{field: 'dingding_user_id', title: '用户名'}
        ,{field: 'blong', title: '所属'}
        ,{field: 'data_date', title: '日期'}
          ,{field: 'name', title: '姓名'}
        ,{field: 'phone', title: '手机号'}
        ,{field: 'feedback_short', title: '反馈'}
        ,{field: 'feedback_detail', title: '跟进记录'}
          ,{field: 'dingding_user_name', title: '资源所属人'}
        ,{fixed: 'right', width:200, align:'center', toolbar: '#bar_feedback'} //这里的toolbar值是模板元素的选择器
    ]],
      defaultToolbar: ['filter', {
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
          alert('本页面仅支持查看，请去反馈页面操作');
        // modal_data_func(layEvent,data)
        // open_modal(1,'更新','modal_feeedback')
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
                console.log('add');
                alert('本页面仅支持查看，请去反馈页面操作');
                // modal_data_func(obj.event,[])
                // open_modal(1,'新增','modal_feeedback')
                break;
            case 'bind_ec_info':
                console.log('bind_ec_info')
                alert('本页面仅支持查看，请去反馈页面操作');
                // let user_info = JSON.parse(localStorage.getItem("user_info"))
                // if((user_info.is_bind_ec).length == 0){ // "",[]
                //     console.log("为空");
                //     //弹出modal
                //     modal_ec_bind()
                //     open_modal(1,'绑定EC用户','modal_bind_ec')
                // }else{
                //     console.log("不为空");
                //     //不为空，则已绑定过，禁止绑定
                //     alert('EC关系已绑定，联系管理员');
                // }
        };
    });

    let form = layui.form
        , laydate = layui.laydate;
    //日期
    laydate.render({
        elem: '#filter_date'
        , value: new Date()
        , isInitValue: false //是否允许填充初始值，默认为 true
    });

    form.on('submit(*)', function(data){
        // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
        // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
        console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
        let where_data = data.field;
        where_data.user_id = dngding_user_id;
        table.reload('feedback', {
            url: '/get_manage_feedback_list'
            ,where: where_data //设定异步数据接口的额外参数
            //,height: 300
        });
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });


});

function open_modal(type,title='信息',content='modal_feeedback'){
        layer.open({
          type: type,
          title:title,
          content: $('#'+content+'') //这里content是一个普通的String
        });
}
function modal_data_func(layEvent,data) {
    console.log(layEvent);
    console.log(data);
    layui.use(['form', 'layedit', 'laydate'], function () {
        var form = layui.form
            , layer = layui.layer
            , layedit = layui.layedit
            , laydate = layui.laydate;

        //日期
        laydate.render({
            elem: '#data_date'
            , value: new Date()
            , isInitValue: true //是否允许填充初始值，默认为 true
        });
        //先做判断类型，进行添加值
        let assignment = {}

        switch (layEvent) {
            case 'edit':
                assignment.blong = data.blong;
                assignment.data_date = data.data_date;
                assignment.name = data.name;
                assignment.phone = data.phone;
                assignment.feedback_short = data.feedback_short;
                assignment.feedback_detail = data.feedback_detail;
                assignment.dingding_user_name = data.dingding_user_name;

                $("#sub_btn").html('修改');
                $("#sub_btn").show();
                get_opera_data('edit', data, data.dingding_user_id) //操作的方法的集合
                break;
            case 'detail':
                assignment.blong = data.blong;
                assignment.data_date = data.data_date;
                assignment.name = data.name;
                assignment.phone = data.phone;
                assignment.feedback_short = data.feedback_short;
                assignment.feedback_detail = data.feedback_detail;
                assignment.dingding_user_name = data.dingding_user_name;
                //隐藏掉提交按钮
                $("#sub_btn").hide();
                break;
            case 'add':
                //为所属进行赋值
                assignment.blong = '';
                let user_info = JSON.parse(localStorage.getItem("user_info"))
                let show_user_name = user_info.data.department_name + user_info.data.position + '--' + user_info.data.name

                let project_arr = <?php echo json_encode($project_list);?>;
                console.log(project_arr)
                for (x in project_arr) {
                    console.log(project_arr[x].project_name)
                    if (show_user_name.indexOf(project_arr[x].project_name) !== -1) {
                        //包含此項目名稱
                        assignment.blong = project_arr[x].project_name;
                    } else {
                        //跳過
                        continue;
                    }
                }
                let dingding_user_id = user_info.data.id;

                assignment.name = '';
                assignment.phone = '';
                assignment.feedback_short = '';
                assignment.feedback_detail = '';
                assignment.dingding_user_name = '';
                $("#sub_btn").html('新增');
                $("#sub_btn").show();
                get_opera_data('add', data, dingding_user_id) //操作的方法的集合
                break;
        }

        console.log(assignment);
        //表单赋值
        form.val('feedback', assignment)

        //获取表单值并请求后台
        function get_opera_data(opera_type, originally_data, user_id) {

            $("#sub_btn").unbind("click")

            layui.$('#sub_btn').on('click', function () {
                var data = form.val("feedback");
                // alert(JSON.stringify(data));
                //这边进行请求方法，更新与增加的集合
                $.ajax({
                    //请求方式
                    type: "POST",
                    //请求的媒体类型
                    contentType: "application/json;charset=UTF-8",
                    //请求地址
                    url: "/manage_feedback_opera_data",
                    //数据，json字符串
                    data: JSON.stringify({
                        "opera_type": opera_type,
                        "originally_data": originally_data,
                        "dingding_user_id": user_id,
                        "latest_data": data
                    }),//JSON.stringify(list),
                    //请求成功
                    success: function (result) {
                        console.log(result);
                        layer.msg(result.msg);
                        location.reload();

                    },
                    //请求失败，包含具体的错误信息
                    error: function (e) {
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

//对于搜索进行处理
// layui.use(['form','laydate','table',], function(){
//     let form = layui.form
//         , laydate = layui.laydate;
//     //日期
//     laydate.render({
//         elem: '#filter_date'
//         , value: new Date()
//         , isInitValue: false //是否允许填充初始值，默认为 true
//     });
//
//     form.on('submit(*)', function(data){
//         // console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
//         // console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
//         console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
//
//         return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
//     });
// });

</script>
<script type="text/html" id="bar_feedback">

  <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
{{--  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>--}}

</script>



@endsection
