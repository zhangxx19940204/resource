<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\CheckRecord;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckRecordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '钉钉打卡记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CheckRecord());

        $dingding_user_list = DB::table('dingding_user')->get();
        $dingding_user_arr = [];
        foreach ($dingding_user_list as $k=>$v){
            $dingding_user_arr[$v->userid] = $v->department_name.$v->name.$v->userid;
        }

        $grid->column('id', __('编号'));
        $grid->column('event_type', __('事件类型'))->display(function ($event_type){
            if ($event_type == 'attendance_check_record'){
                return '员工打卡';
            }else{
                return $event_type;
            }
        });
        $grid->column('user_id', __('用户'))->display(function (){
            return $this->ding_user_info->department_name.$this->ding_user_info->name;
        });
        $grid->column('checkTime', __('打卡时间'));
        $grid->column('locationMethod', __('打卡方式'))->display(function ($LocationMethod){
            if ($LocationMethod == 'MAP'){
                return '定位打卡';
            }elseif ($LocationMethod == 'WIFI'){
                return 'wifi打卡';
            }elseif ($LocationMethod == 'ATM'){
                return '考勤机打卡或考勤机蓝牙打卡';
            }else{
                return $LocationMethod;
            }
        });
        $grid->column('locationResult', __('定位'))->display(function ($locationResult){
            if ($locationResult == 'Normal'){
                return '内勤';
            }elseif ($locationResult == 'Outside'){
                return '外勤';
            }else{
                return $locationResult;
            }
        });
        $grid->column('data', __('源数据'))->display(function (){
            return '点击查看';
        })->modal('数据源数据', function ($model) {
            $data_arr= json_decode($model->data,true);
            $key_arr = array_keys($data_arr);
            $data_val = [];
            foreach ($key_arr as $k=>$value){
                //判断值是否为其他类型
                if (is_array($data_arr[$value])){
                    //当前值为数组
                    $data_val[] = ['key'=>$value,'value'=>json_encode($data_arr[$value],JSON_UNESCAPED_UNICODE)];
                }else{
                    //不是数组直接赋值
                    $data_val[] = ['key'=>$value,'value'=>$data_arr[$value]];
                }
            }
            return new Table(['名称','值'], $data_val);

        });
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        $grid->model()->orderBy('checkTime', 'desc');
        $grid->disableCreateButton();
        $grid->actions(function ($actions){
            // 去掉删除
            $actions->disableDelete();
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();
        });
        //导出配置
        $grid->export(function ($export) {

            $export->filename(date('YmdHis').'-用户打卡记录.csv');

            $export->only(['event_type','user_id','checkTime','locationMethod','locationResult']);

            $export->column('created_at', function ($value, $original) {
                return $value;
            });
            $export->column('config_id', function ($value, $original) {
                return $value;
            });

        });

        $grid->filter(function ($filter) use($dingding_user_arr) {

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏
            $filter->in('user_id','钉钉用户')->multipleSelect($dingding_user_arr);
            // 打卡时间 字段的范围查询
            $filter->between('checkTime', '打卡时间')->datetime();

        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(CheckRecord::findOrFail($id));

        // $show->field('id', __('Id'));
        // $show->field('event_type', __('Event type'));
        // $show->field('data', __('Data'));
        // $show->field('user_id', __('User id'));
        // $show->field('locationMethod', __('LocationMethod'));
        // $show->field('locationResult', __('LocationResult'));
        // $show->field('checkTime', __('CheckTime'));
        // $show->field('created_at', __('Created at'));
        // $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new CheckRecord());

        // $form->text('event_type', __('Event type'));
        // $form->text('data', __('Data'));
        // $form->text('user_id', __('User id'));
        // $form->text('locationMethod', __('LocationMethod'));
        // $form->text('locationResult', __('LocationResult'));
        // $form->datetime('checkTime', __('CheckTime'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
