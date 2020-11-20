<?php

namespace App\Admin\Controllers\Res;

use App\Models\ResData;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class ResDataController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '资源统计';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ResData());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('用户ID'));
        $grid->column('config_id', __('Config id'));

        $grid->column('data_name', __('姓名'));
        $grid->column('data_phone', __('电话'));
        $grid->column('belong', __('所属'));
        $grid->column('type', __('类型'));

        $grid->column('created_at', __('入库时间'))->display(function ($created_at){
            return date('Y-m-d H:i:s',strtotime($created_at));
        });
        $grid->column('updated_at', __('更新时间'))->display(function ($updated_at){
            return date('Y-m-d H:i:s',strtotime($updated_at));
        });

        $grid->column('remarks', __('备注'));

        $grid->column('data_json', __('源数据'))->display(function (){
            return '123456';
        })->modal('数据源数据', function ($model) {
            $data_arr= json_decode($model->data_json,true);
            $key_arr = array_keys($data_arr);
            $data_val = [];
            foreach ($key_arr as $k=>$value){
                $data_val[] = ['key'=>$value,'value'=>$data_arr[$value]];
            }
            return new Table(['key','value'], $data_val);

        });

        $grid->model()->orderBy('id', 'desc');

        $grid->disableCreateButton();

        $grid->filter(function ($filter) {

            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏

            $filter->column(1/2, function ($filter) {
                $filter->ilike('config_id', '账户信息');
                $filter->ilike('belong', '所属');
                // 设置created_at字段的范围查询
                $filter->between('created_at', '创建时间')->datetime();
            });

            $filter->column(1/2, function ($filter) {
                $filter->ilike('type', '类型');
                $filter->ilike('data_phone', '客户电话');
                $filter->ilike('data_name', '客户姓名');
                $filter->between('updated_at', '更新时间')->datetime();
            });


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
        $show = new Show(ResData::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('config_id', __('Config id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('last_para', __('Last para'));
        $show->field('remarks', __('Remarks'));
        $show->field('belong', __('Belong'));
        $show->field('type', __('Type'));
        $show->field('data_json', __('Data json'));
        $show->field('data_name', __('Data name'));
        $show->field('data_phone', __('Data phone'));
        $show->field('data_request_id', __('Data request id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ResData());

        $form->number('user_id', __('User id'));
        $form->number('config_id', __('Config id'));
        $form->text('last_para', __('Last para'));
        $form->text('remarks', __('Remarks'));
        $form->text('belong', __('Belong'));
        $form->text('type', __('Type'));
        $form->text('data_json', __('Data json'));
        $form->text('data_name', __('Data name'));
        $form->text('data_phone', __('Data phone'));
        $form->text('data_request_id', __('Data request id'));

        return $form;
    }
}
