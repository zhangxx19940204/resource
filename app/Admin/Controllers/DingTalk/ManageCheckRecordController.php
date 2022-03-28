<?php

namespace App\Admin\Controllers\DingTalk;

use App\Models\DingTalk\ManageCheckRecord;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class ManageCheckRecordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '打卡汇总记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ManageCheckRecord());
        $states = [
            'on'  => ['value' => 1],
            'off' => ['value' => 0],
        ];

        $dingding_user_list = DB::table('dingding_user')->get();
        $dingding_user_arr = [];
        foreach ($dingding_user_list as $k=>$v){
            $dingding_user_arr[$v->userid] = $v->department_name.$v->name;
        }

        $dingding_user_checkrecord = DB::table('dingding_user_checkrecord')->get();
        $dingding_checkrecord_arr = [];
        foreach ($dingding_user_checkrecord as $k1=>$v1){
            $dingding_checkrecord_arr[$v1->user_id] = $v1->checkTime;
        }


        $grid->column('id', __('编号'));
        $grid->column('manage_user', __('管理人'))->display(function (){
            if (empty($this->ding_user_info)){
                return '';
            }else{
                return ($this->ding_user_info)['department_name'].($this->ding_user_info)['name'];
            }

        });
        $grid->column('employee', __('员工列表'))->display(function ($employee) use($dingding_user_arr,$dingding_checkrecord_arr) {
            $dingTalkUserStr = '';
            foreach ($employee as $value){
                if (array_key_exists($value,$dingding_checkrecord_arr)){
                    $dingding_checkrecord_data = $dingding_checkrecord_arr[$value];
                }else{
                    $dingding_checkrecord_data = '未知';
                }
                if (array_key_exists($value,$dingding_user_arr)){
                    $dingding_user_data = $dingding_user_arr[$value];
                }else{
                    $dingding_user_data = '未知';
                }
                $dingTalkUserStr .= $dingding_user_data.'打卡时间：<span style="color: chocolate;">'.$dingding_checkrecord_data.'</span><br/>';
            }
            return $dingTalkUserStr;

        });
        $grid->column('status', __('是否启用'))->switch($states);
        $grid->column('monitor_start', __('监测打卡开始时间'));
        $grid->column('monitor_end', __('监测打卡结束时间'));
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(ManageCheckRecord::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('manage_user', __('Manage user'));
        $show->field('employee', __('Employee'));
        $show->field('status', __('Status'));
        $show->field('monitor_start', __('Monitor start'));
        $show->field('monitor_end', __('Monitor end'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ManageCheckRecord());

        $dingding_user_list = DB::table('dingding_user')->get();
        $dingding_user_arr = [];
        foreach ($dingding_user_list as $k=>$v){
            $dingding_user_arr[$v->userid] = $v->department_name.$v->name;
        }
        $form->select('manage_user', __('管理者'))->options($dingding_user_arr);
        $form->listbox('employee', __('员工列表'))->options($dingding_user_arr);
        $form->switch('status', __('是否启用'));
        $form->text('monitor_start', __('监测打卡时间开始'));
        $form->text('monitor_end', __('监测打卡时间结束'));

        return $form;
    }
}
