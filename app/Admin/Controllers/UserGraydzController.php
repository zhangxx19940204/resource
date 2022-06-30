<?php

namespace App\Admin\Controllers;

use App\Models\EcUser;
use App\Models\UserGraydz;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserGraydzController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户成绩表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new UserGraydz());
        $user_list = EcUser::get(['id','userName','deptName'])->toarray();
        $user_arr = [];
        foreach ($user_list as $k=>$v){
            $user_arr[$v['id']] = $v['deptName'];
        }

        $grid->column('id', __('编号'));
        $grid->column('ec_user_id', __('用户'))->display(function ($ec_user_id) use($user_arr)  {
            if (empty($ec_user_id)){
                return '';
            }
            if (array_key_exists($ec_user_id,$user_arr)){
                return $user_arr[$ec_user_id];
            }else{
                return '';
            }

        });
        $grid->column('date', __('进款日期'));
        $grid->column('res_number', __('资源量'));
        $grid->column('visit_number', __('来访'));
        $grid->column('agency_number', __('签约'));
        $grid->column('incom_payments', __('进款'));
        $grid->column('created_at', __('创建日期'));
        $grid->model()->orderBy('id', 'desc');
        $grid->actions(function ($actions){
            // 去掉删除
            // $actions->disableDelete();
            // 去掉编辑
            // $actions->disableEdit();
            // 去掉查看
            $actions->disableView();

        });

        $grid->filter(function ($filter) use($user_arr){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏
            $filter->in('ec_user_id', 'ec用户')->multipleSelect($user_arr);
            // 日期查询
            $filter->between('date', '进款日期')->date();
            $filter->between('created_at', '创建日期')->datetime();


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
        $show = new Show(UserGraydz::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ec_user_id', __('ec user id'));
        $show->field('date', __('Date'));
        $show->field('res_number', __('Res number'));
        $show->field('visit_number', __('Visit number'));
        $show->field('agency_number', __('Agency number'));
        $show->field('incom_payments', __('Incom payments'));
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
        $form = new Form(new UserGraydz());

        $user_list = EcUser::get(['id','userName','deptName'])->toarray();
        $user_arr = [];
        foreach ($user_list as $k=>$v){
            $user_arr[$v['id']] = $v['deptName'];
        }

        $form->select('ec_user_id', __('EC用户'))->options($user_arr);
        $form->date('date', __('进款日期'))->default(date('Y-m-d'));
        $form->text('res_number', __('资源量'));
        $form->text('visit_number', __('来访'));
        $form->text('agency_number', __('签约'));
        $form->text('incom_payments', __('进款'));

        return $form;
    }

    public function get_echarts(Content $content){

        return $content->title('成绩展示')
            ->description('成绩展示')
            ->view('admin.graydz.show', []);
    }
}
