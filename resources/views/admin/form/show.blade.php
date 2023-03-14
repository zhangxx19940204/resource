<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>资源统计后台首页</title>
    <link rel="stylesheet" type="text/css" href="/assets/admin/home/css/style.css" />
    <script type="text/javascript" src="/assets/admin/home/js/echarts.min.js"></script>

    <script type="text/javascript" src="/assets/admin/home/js/moment.min.js"></script>
    <script type="text/javascript" src="/assets/admin/home/js/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/admin/home/css/daterangepicker.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/form/layui.css" />
</head>

<body>
<style>
    .cont {
        padding-left: 6%;
    }
    .search_input {
        height: 15%;
    }


    input {
        height: 40px;
        line-height: 40px;
        text-align: center;
        width: 201px;
    }
    .btn-default {
        background-color: #fff;
        border: 1px solid #828080;
        border-radius: 3px;
        cursor: pointer;
    }
    .btn-primary {
        background-color: #08c;
        border: 1px solid #08c;
        border-radius: 3px;
        color: #fff;
        cursor: pointer;
    }
    .btn-primary:hover{
        background-color: #357ebd;
    }
</style>

<div class="demoTable">
    搜索：
    <div class="layui-inline">
        <input class="layui-input" name="search" id="search_input" autocomplete="off">
    </div>
    <button class="layui-btn" data-type="reload">搜索</button>
</div>

<table class="layui-hide" id="form_table" lay-filter="form_table"></table>



<script src="/assets/admin/form/layui.js"></script>
<!--您的Layui代码start-->
<script type="text/javascript">
    layui.use(['laydate', 'laypage', 'layer', 'table', 'carousel', 'upload', 'element'], function() {
        let laydate = layui.laydate //日期
            ,laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            ,table = layui.table //表格
            ,carousel = layui.carousel //轮播
            ,upload = layui.upload //上传
            ,element = layui.element; //元素操作 等等...

        table.render({
            elem: '#form_table'
            ,url: '/admin/get_form_data'
            ,cellMinWidth: 180 //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            ,page: { //支持传入 laypage 组件的所有参数（某些参数除外，如：jump/elem） - 详见文档
                // layout: ['limit', 'count', 'prev', 'page', 'next', 'skip'] //自定义分页布局
                curr: 1 //设定初始在第 5 页
                ,groups: 1 //只显示 1 个连续页码
                ,first: false //不显示首页
                ,last: false //不显示尾页

            }
            ,cols: [[
                {field:'id', width:80, title: 'ID', sort: true}
                ,{field:'name', title: '表单名'}
                ,{field:'original_str', title: '用户提交'}
                ,{field:'create_time', title: '创建时间'}
                ,{field:'update_time', title: '更新时间'}
                ,{field:'title', title: '打印', width: 200
                    ,templet: function(d){
                        console.log(d.LAY_INDEX); //得到序号。一般不常用
                        console.log(d.LAY_COL); //得到当前列表头配置信息（layui 2.6.8 新增）。一般不常用
                        let id = d.id;
                        //得到当前行数据，并拼接成自定义模板
                        return '<div><a target="_blank" href="/admin/print_form_data/'+id+'" class="layui-table-link">打印</a></div>';
                        // return 'ID：'+ d.id +'，标题：<span style="color: #c00;">'+ d.title +'</span>'
                    }
                }

            ]]
            ,parseData: function(res){ //将原始数据解析成 table 组件所规定的数据
                return {
                    "code": res.code, //解析接口状态
                    "msg": res.message, //解析提示文本
                    "count": res.count, //解析数据长度
                    "data": res.data //解析数据列表
                };
            }
        });


        $('.demoTable .layui-btn').on('click', function(){

            let search_input = $("#search_input").val();
            //重载表格
            //执行重载
            table.reload('form_table', {
                page: {
                    curr: 1 //重新从第 1 页开始
                }
                ,where: {
                    key: {
                        search:search_input
                    }
                }
            });

        });

    });



</script>


</body>

</html>
