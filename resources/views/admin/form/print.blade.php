<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>资源统计后台首页</title>
    <link rel="stylesheet" type="text/css" href="/assets/admin/home/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/assets/admin/form/designed-print-wenjuan.css" />
    <script type="text/javascript" src="/assets/admin/home/js/echarts.min.js"></script>
    <script type="text/javascript" src="/assets/admin/form/print-wenjuan.js"></script>

    <script type="text/javascript" src="/assets/admin/home/js/moment.min.js"></script>
    <script type="text/javascript" src="/assets/admin/home/js/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/assets/admin/home/css/daterangepicker.css" />
</head>

<body>




<div class="container prints-container">
    <div class="prints-box">
        <div class="prints-content">



            <div class="tab-content">
                <div class="tab-pane active" id="designed_print">
                    <div class="pages-container" data-serial-numbers="" data-has-printable-columns="true">
                        <!-- <div class="batch-entry"> -->
                        <div class="entry">
                            <div class="title">
                                <div class="form-name">{{$title}}</div>
                                <div class="serial-number item_serial_number hide">#</div>
                            </div>


                            <div class="entry-details">
                                <table>
                                    <tbody>
                                        <?php
                                            // var_dump($form_data_arr);
                                            foreach ($form_data_arr as $key=>$value){
                                                ?>

                                            <!-- 循环表单数据 -->
                                        <tr class="field-item item_field_1  ">
                                            <td class="first-item"> {{$value['label']}} </td>
                                            <td><div class="textarea"><p>{{$value['value']}}</p></div></td>
                                        </tr>
                                            <?php  } ?>




                                    </tbody>
                                </table>
                            </div>

                        </div>

{{--                        <div class="logo">--}}
{{--                            <div> <i class="gd-icon-logo"></i> </div>--}}
{{--                            <div class="brand"></div>--}}
{{--                        </div>--}}
                        <!-- </div> -->

                    </div>
                </div>
                <div class="tab-pane" id="simple_print">
                </div>

            </div>
        </div>
    </div>


{{--    <aside>--}}

        <div class="prints-actions">
            <div class="loading-container text-center" style="display: none;">
                <div class="loading-progress text-muted ">数据加载中...  (1/0)</div>
            </div>
            <button name="button" type="submit" class="gd-btn gd-btn-block gd-btn-primary-solid print-btn" data-disable-with="加载中...">打印</button>
        </div>

{{--    </aside>--}}

</div>



</body>

</html>
