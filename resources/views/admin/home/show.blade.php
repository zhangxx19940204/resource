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

<div class="case_box">
    <h3>数据统计</h3>
    <div class="category w1200">
        <ul>
            <li class="active">每日资源汇总</li>
            <li>待开发</li>
            <li>待开发</li>
            <li>待开发</li>
            <li>待开发</li>
        </ul>
    </div>
    <div class="content w1200">
        <div class="cont active">
            <div class="search_input">
                <input id="date_main1"></input>
            </div>
            <div id="main1" style="width: 85%;height:70%;"></div>
        </div>
        <div class="cont">
            待开发
        </div>
        <div class="cont">
            待开发
        </div>
        <div class="cont">
            待开发
        </div>
        <div class="cont">
            待开发
        </div>
    </div>
</div>
<!--选项卡-->
<script type="text/javascript">
    $(function() {
        $('.category ul li').click(function() {
            var i = $(this).index();
            $(this).addClass('active').siblings().removeClass('active');
            $('.content .cont').eq(i).addClass('active').siblings().removeClass('active');
        })

    });
</script>

<script type="text/javascript">
    $(function() {
        // 基于准备好的dom，初始化echarts实例
        let myChart = echarts.init(document.getElementById('main1'));
        // 指定图表的配置项和数据
        let option = {
            title: {
                text: '每日资源总汇'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: []
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {}
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: []
            },
            yAxis: {
                type: 'value'
            },
            series: []
        };
        myChart.setOption(option);

        // 使用刚指定的配置项和数据显示图表。
        if (option && typeof option === 'object') {
            myChart.setOption(option);
        }

        //时间选择器
        let locale = {
            "format": 'YYYY-MM-DD',
            "separator": " - ",
            "applyLabel": "确定",
            "cancelLabel": "取消",
            "fromLabel": "起始时间",
            "toLabel": "结束时间'",
            "customRangeLabel": "自定义",
            "weekLabel": "W",
            "daysOfWeek": ["日", "一", "二", "三", "四", "五", "六"],
            "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月", "十月", "十一月", "十二月"],
            "firstDay": 1
        };
        $('#date_main1').daterangepicker({
            'locale': locale,
            ranges: {
                '今日': [moment(), moment()],
                '昨日': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '最近7日': [moment().subtract(6, 'days'), moment()],
                '最近30日': [moment().subtract(29, 'days'), moment()],
                '本月': [moment().startOf('month'), moment().endOf('month')],
                '上月': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month')
                    .endOf('month')
                ]
            },
            "alwaysShowCalendars": true,
            "startDate": new Date(),
            "endDate": new Date(),
            "opens": "right",
        }, function (start, end, label) {
            console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            let startDate = start.format('YYYY-MM-DD')
            let endDate = end.format('YYYY-MM-DD')
            $.ajax({
                url:'/admin/get_resource_all_by_day',
                type:'POST', //GET
                async:true,    //或false,是否异步
                data:{
                    startDate:startDate,
                    endDate:endDate
                },
                timeout:5000,    //超时时间
                dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                beforeSend:function(xhr){
                    console.log(xhr)
                    console.log('发送前')
                },
                success:function(data,textStatus,jqXHR){
                    console.log(data);
                    myChart.setOption({
                        xAxis: {
                            data: data.xAxis_data
                        },
                        series: data.series_data,
                        legend: {
                            data: data.legend_data
                        },
                    });
                },
                error:function(xhr,textStatus){
                    console.log('错误',xhr.responseText);
                }

            })

        });

    });

</script>


</body>

</html>
