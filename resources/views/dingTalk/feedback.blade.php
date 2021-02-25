@extends('dingTalk.layouts.default')

@section('feedback', 'active')
@section('visit', '')

@section('sidebar')
@parent
@endsection

@section('content')

<link rel="stylesheet" href="https://www.layuicdn.com/layui/css/layui.css" media="all">


 
<script src="https://www.layuicdn.com/layui/layui.js"></script>

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
  //第一个实例
  table.render({
    elem: '#feedback'
    ,toolbar:true
    // ,height: 1080
    // ,width: 1080
    ,toolbar:'#toolbar_header'
    ,url: '/get_list' //数据接口
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
        ,{field: 'phone', title: '手机号'}
        ,{field: 'feedback_short', title: '反馈'}
        ,{field: 'feedback_detail', title: '跟进记录'}
        ,{fixed: 'right', width:200, align:'center', toolbar: '#bar_feedback'} //这里的toolbar值是模板元素的选择器
    ]]
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
        
      };
    });
  
  
  
});

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
        assignment.phone = data.phone;
        assignment.feedback_short = data.feedback_short;
        assignment.feedback_detail = data.feedback_detail;
        $("#sub_btn").html('修改');
        $("#sub_btn").show();
        get_opera_data('edit',data,data.dingding_user_id) //操作的方法的集合
        break;
    case 'detail':
        assignment.blong = data.blong;
        assignment.data_date = data.data_date;
        assignment.phone = data.phone;
        assignment.feedback_short = data.feedback_short;
        assignment.feedback_detail = data.feedback_detail;
        //隐藏掉提交按钮
        $("#sub_btn").hide();
         break;
    case 'add':
        //为所属进行赋值
        assignment.blong = '';
        let user_info = JSON.parse(localStorage.getItem("user_info"))
        let show_user_name = user_info.data.department_name+user_info.data.position + '--' +user_info.data.name
        if(show_user_name.indexOf("腩潮鲜") !== -1){
            //包含腩潮鲜 
            assignment.blong = '腩潮鲜';
            
        }else if(show_user_name.indexOf("半城外") !== -1){
            //包含半城外
            assignment.blong = '半城外';
            
        }else if(show_user_name.indexOf("原时") !== -1){
            //包含原时
            assignment.blong = '原时烤肉';
            
        }else if(show_user_name.indexOf("下江腩") !== -1){
            //包含原时
            assignment.blong = '下江腩';
            
        }else if(show_user_name.indexOf("阿城牛货") !== -1){
            //包含原时
            assignment.blong = '阿城牛货';
            
        }else if(show_user_name.indexOf("隐匠") !== -1){
            //包含原时
            assignment.blong = '隐匠';
            
        }else{
            assignment.blong = '';
        }
        let dingding_user_id = user_info.data.id;
        
        assignment.phone = '';
        assignment.feedback_short = '';
        assignment.feedback_detail = '';
        
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
</script>
<script type="text/html" id="bar_feedback">

  <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
  
</script>



@endsection
