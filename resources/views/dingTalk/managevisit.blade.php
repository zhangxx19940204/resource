@extends('dingTalk.layouts.default')

@section('feedback', '')
@section('visit', '')
@section('manage_feedback', '')
@section('manage_visit', 'active')

@section('sidebar')
@parent
@endsection


@section('modal')
    @parent
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

                        @forelse ($project_list as $project)
                            <option value="{{ $project->project_name }}">{{ $project->project_name }}</option>
                        @empty

                        @endforelse


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
            <!--进款日期-->
            <div class="layui-form-item">
                <label class="layui-form-label">进款日期</label>
                <div class="layui-input-inline">
                    <input type="text" name="payment_date" id="payment_date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
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
@endsection



@section('content')
<link href="{{ asset('dingTalk/investment/layui.css') }}" rel="stylesheet"/>
<script src="https://cdn.bootcdn.net/ajax/libs/layui/2.6.8/layui.min.js"></script>

<form class="layui-form" lay-filter="filter_visit"> <!-- 提示：如果你不想用form，你可以换成div等任何一个普通元素 -->

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
        <label class="layui-form-label">来访日期</label>
        <div class="layui-input-block">
            <input type="text" name="filter_date" placeholder="来访日期" id="filter_date" class="layui-input">
        </div>
    </div>

    <div class="layui-form-item">
        <label class="layui-form-label">手机号</label>
        <div class="layui-input-block">
            <input type="text" lay-verify="" name="filter_phone" placeholder="手机号" class="layui-input">
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


<table id="visit" lay-filter="visit"></table>
<script type="text/html" id="toolbar_header">
  <div class="layui-btn-container">
{{--    <button class="layui-btn layui-btn-sm" lay-event="add">添加</button>--}}
  </div>
</script>

<script>
layui.use(['table','form','laydate',], function(){
  let table = layui.table;
  let user_info = JSON.parse(localStorage.getItem("user_info"))
  let dngding_user_id = user_info.data.id
  //第一个实例
  table.render({
    elem: '#visit'
    ,toolbar:true
    // ,height: 312
    // ,width: 1080
    ,toolbar:'#toolbar_header'
    ,url: '/get_manage_visit_list' //数据接口
    ,where: {user_id: dngding_user_id} //如果无需传递额外参数，可不加该参数
    ,method: 'post' //如果无需自定义HTTP类型，可不加该参数
  //request: {} //如果无需自定义请求参数，可不加该参数
  //response: {} //如果无需自定义数据响应名称，可不加该参数
    ,page: true //开启分页
    ,cols: [[ //表头
         {field: 'id', title: 'ID'}
        // ,{field: 'dingding_user_id', title: '用户'}
        ,{field: 'visit_name', title: '客户姓名',width:80}
        ,{field: 'phone', title: '手机号',width:118}
        ,{field: 'blong', title: '所属'}
        ,{field: 'visit_month', title: '月份'}
        ,{field: 'visit_date', title: '来访日期'}
          ,{field: 'payment_date', title: '进款日期'}
        ,{field: 'visit_brand', title: '品牌'}

        ,{field: 'visit_sex', title: '性别'}
        ,{field: 'visit_result', title: '来访结果'}
        ,{field: 'money_type', title: '进款分类'}
        ,{field: 'money_enter', title: '入款'}
        ,{field: 'pending_closing', title: '待收尾款'}
        ,{field: 'shop_type', title: '店型'}
        ,{field: 'invitee', title: '邀约人'}
        ,{field: 'negotiation_manager', title: '谈判经理'}
        ,{field: 'department', title: '部门'}
        ,{field: 'resource_platform', title: '资源平台'}
        ,{field: 'include_time', title: '录入时间'}
        // ,{field: 'phone', title: '手机号'}
        ,{field: 'address', title: '地址'}
        ,{field: 'age', title: '年龄'}
        ,{field: 'occupational', title: '职业'}
        ,{field: 'reason_not_signed', title: '未签原因'}
        ,{field: 'is_partner', title: '是否有合伙人'}
        ,{field: 'visit_cycle', title: '来访周期（距离拿资源时间）'}
        ,{field: 'signing_cycle', title: '签约周期'}
          ,{field: 'dingding_user_name', title: '资源所属人'}
        ,{fixed: 'right', width:200, align:'center', toolbar: '#bar_visit'} //这里的toolbar值是模板元素的选择器
    ]],
      defaultToolbar: ['filter']
  });

   //监听工具条
    table.on('tool(visit)', function(obj){ //注：tool 是工具条事件名，feedback 是 table 原始容器的属性 lay-filter="对应的值"
      var data = obj.data; //获得当前行数据
      var layEvent = obj.event; //获得 lay-event 对应的值（也可以是表头的 event 参数对应的值）
      var tr = obj.tr; //获得当前行 tr 的 DOM 对象（如果有的话）

      if(layEvent === 'detail'){ //查看
        //do somehing
        console.log('detail')
        modal_data_func(layEvent,data)
        open_modal(1,'展示','modal_visit')

      }  else if(layEvent === 'edit'){ //编辑
        //do something
        console.log('edit')
          alert('本页面仅支持查看，请去反馈页面操作')
        // modal_data_func(layEvent,data)
        // open_modal(1,'更新','modal_visit')
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
    table.on('toolbar(visit)', function(obj){
      var checkStatus = table.checkStatus(obj.config.id);
      switch(obj.event){
        case 'add':
            console.log('add')
            alert('本页面仅支持查看，请去反馈页面操作');
            // modal_data_func(obj.event,[])
            // open_modal(1,'新增','modal_visit')
        break;

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
        table.reload('visit', {
            url: '/get_manage_feedback_list'
            ,where: where_data //设定异步数据接口的额外参数
            //,height: 300
        });
        return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
    });


});

function open_modal(type,title='信息',content='modal_visit'){
        layer.open({
          type: type,
          title:title,
          content: $('#'+content+'') //这里content是一个普通的String
        });
}

//开始清理下面这个方法中的内容和赋值等
function modal_data_func(layEvent,data){
    console.log(layEvent);
    console.log(data);
    layui.use(['form', 'layedit', 'laydate'], function(){
        var form = layui.form
        ,layer = layui.layer
        ,layedit = layui.layedit
        ,laydate = layui.laydate;

        //月份
        laydate.render({
            elem: '#visit_month'
            ,type: 'month'
            // ,value: new Date()
            ,isInitValue: true //是否允许填充初始值，默认为 true
        });
        //来访日期
        laydate.render({
            elem: '#visit_date'
            ,type: 'date'
            // ,value: new Date()
            ,isInitValue: true //是否允许填充初始值，默认为 true
        });
        //进款日期
        laydate.render({
            elem: '#payment_date'
            ,type: 'date'
            // ,value: new Date()
            ,isInitValue: true //是否允许填充初始值，默认为 true
        });
        //录入时间
        laydate.render({
            elem: '#include_time'
            ,type: 'datetime'
            // ,value: new Date()
            ,isInitValue: true //是否允许填充初始值，默认为 true
        });

    //先做判断类型，进行添加值
    let assignment = {}

    switch (layEvent) {
    case 'edit':
        assignment.blong = data.blong
        assignment.visit_month = data.visit_month
        assignment.visit_date = data.visit_date
        assignment.payment_date = data.payment_date
        assignment.visit_brand = data.visit_brand
        assignment.visit_name = data.visit_name
        assignment.visit_sex = data.visit_sex
        assignment.visit_result = data.visit_result
        assignment.money_type = data.money_type
        assignment.money_enter = data.money_enter
        assignment.pending_closing = data.pending_closing
        assignment.shop_type = data.shop_type
        assignment.invitee = data.invitee
        assignment.negotiation_manager = data.negotiation_manager
        assignment.department = data.department
        assignment.resource_platform = data.resource_platform
        assignment.include_time = data.include_time
        assignment.phone = data.phone
        assignment.address = data.address
        assignment.age = data.age
        assignment.occupational = data.occupational
        assignment.reason_not_signed = data.reason_not_signed
        assignment.is_partner = data.is_partner
        assignment.visit_cycle = data.visit_cycle
        assignment.signing_cycle = data.signing_cycle

        $("#visit_sub_btn").html('修改');
        $("#visit_sub_btn").show();
        get_opera_data('edit',data,data.dingding_user_id) //操作的方法的集合
        break;
    case 'detail':
        assignment.blong = data.blong
        assignment.visit_month = data.visit_month
        assignment.visit_date = data.visit_date
        assignment.payment_date = data.payment_date
        assignment.visit_brand = data.visit_brand
        assignment.visit_name = data.visit_name
        assignment.visit_sex = data.visit_sex
        assignment.visit_result = data.visit_result
        assignment.money_type = data.money_type
        assignment.money_enter = data.money_enter
        assignment.pending_closing = data.pending_closing
        assignment.shop_type = data.shop_type
        assignment.invitee = data.invitee
        assignment.negotiation_manager = data.negotiation_manager
        assignment.department = data.department
        assignment.resource_platform = data.resource_platform
        assignment.include_time = data.include_time
        assignment.phone = data.phone
        assignment.address = data.address
        assignment.age = data.age
        assignment.occupational = data.occupational
        assignment.reason_not_signed = data.reason_not_signed
        assignment.is_partner = data.is_partner
        assignment.visit_cycle = data.visit_cycle
        assignment.signing_cycle = data.signing_cycle
        //隐藏掉提交按钮
        $("#visit_sub_btn").hide();
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

        assignment.blong = ''
        assignment.visit_month = ''
        assignment.visit_date = ''
        assignment.payment_date = ''
        assignment.visit_brand = ''
        assignment.visit_name = ''
        assignment.visit_sex = ''
        assignment.visit_result = ''
        assignment.money_type = ''
        assignment.money_enter = ''
        assignment.pending_closing = ''
        assignment.shop_type = ''
        assignment.invitee = ''
        assignment.negotiation_manager = ''
        assignment.department = ''
        assignment.resource_platform = ''
        assignment.include_time = ''
        assignment.phone = ''
        assignment.address = ''
        assignment.age = ''
        assignment.occupational = ''
        assignment.reason_not_signed = ''
        assignment.is_partner = ''
        assignment.visit_cycle = ''
        assignment.signing_cycle = ''

        $("#sub_btn").html('新增');
        $("#sub_btn").show();
        get_opera_data('add',data,dingding_user_id) //操作的方法的集合
        break;
    }

    console.log(assignment);
    //表单赋值
    form.val('visit', assignment)

    //获取表单值并请求后台
    function get_opera_data(opera_type,originally_data,user_id){

        $("#visit_sub_btn").unbind("click")

        layui.$('#visit_sub_btn').on('click', function(){
            var data = form.val("visit");
            // alert(JSON.stringify(data));
            //这边进行请求方法，更新与增加的集合
            $.ajax({
                //请求方式
                type : "POST",
                //请求的媒体类型
                contentType: "application/json;charset=UTF-8",
                //请求地址
                url : "/manage_visit_opera_data",
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
<script type="text/html" id="bar_visit">

  <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
{{--  <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>--}}

</script>


@endsection
