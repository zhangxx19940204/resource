<?php

namespace App\Admin\Controllers\Res;

use App\Models\EcUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class EcUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'EC用户';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EcUser());

        $grid->column('id', __('编号'));
//        $grid->column('deptId', __('DeptId'));
        $grid->column('status', __('状态'))->bool();
        $grid->column('deptName', __('部门'));
        $grid->column('userId', __('用户ID'));
        $grid->column('userName', __('用户名'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏
            $filter->column(5/10, function ($filter) {
                $filter->in('status', '状态')->multipleSelect(['0' => '未启用','1'=>'已启用']);
                $filter->equal('userId', '用户ID');
                $filter->equal('userName', '用户名');
                $filter->like('deptName', '部门');
            });

        });

        $grid->export(function ($export) {

            $export->filename('EC用户.csv');

            $export->except(['status',]);

//            $export->originalValue(['column1', 'column2' ...]);
//
//            $export->column('column_5', function ($value, $original) {
//                return $value;
//            });
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
        $show = new Show(EcUser::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('deptId', __('DeptId'));
        $show->field('status', __('Status'));
        $show->field('title', __('Title'));
        $show->field('userId', __('UserId'));
        $show->field('userName', __('UserName'));
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
        $form = new Form(new EcUser());

        $form->number('deptId', __('DeptId'));
        $form->switch('status', __('Status'));
        $form->text('title', __('Title'));
        $form->number('userId', __('UserId'));
        $form->text('userName', __('UserName'));

        return $form;
    }



//    public function get_user_depts1($res,$deptId,$depts){
//        //第一步直接根据部门ID取部门值
//        $res[] = $depts[$deptId]['deptName'];
//
//        //判断对否还有父部门
//        if ($depts[$deptId]['parentDeptId'] == '0'){
//            return $res;
//        }else{
//            return $this->get_user_depts($res,$depts[$deptId]['parentDeptId'],$depts);
//        }
//
//    }
}
