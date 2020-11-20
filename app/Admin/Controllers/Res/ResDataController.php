<?php

namespace App\Admin\Controllers\Res;

use App\Models\ResData;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ResDataController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ResData';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ResData());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('config_id', __('Config id'));

        $grid->column('data_name', __('Data name'));
        $grid->column('data_phone', __('Data phone'));
        $grid->column('belong', __('Belong'));
        $grid->column('type', __('Type'));

        $grid->column('created_at', __('Created at'))->date('Y-m-d H:i:s');
        $grid->column('updated_at', __('Updated at'))->date('Y-m-d H:i:s');
        $grid->column('last_para', __('Last para'));
        $grid->column('remarks', __('Remarks'));

        $grid->column('data_json', __('Data json'));

        $grid->column('data_request_id', __('Data request id'));

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
