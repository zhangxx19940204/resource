<?php

namespace App\Admin\Controllers\Res;

use App\Models\ResDistributionConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\DB;

class ResDistributionConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '配置分配功能';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ResDistributionConfig());

        $grid->column('id', __('Id'));
        $grid->column('belong', __('所属（与资源所属关联）'))->editable();
        $grid->column('recyclable_list', __('可重复使用列表'));
        $grid->column('active_list', __('正在使用的列表'));

        $states = [
            1 => ['value' => 1, 'text' => '打开', 'color' => 'primary'],
            0 => ['value' => 0, 'text' => '关闭', 'color' => 'default'],
        ];
        $grid->column('recyclable', __('是否重复'))->switch($states);
        $grid->column('status', __('启用状态'))->switch($states);
//        $grid->column('enable_time', __('Enable time'));
//        $grid->column('disbale_time', __('Disbale time'));
        $grid->column('created_at', __('创建时间'));
        $grid->column('updated_at', __('更新时间'));

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
        $show = new Show(ResDistributionConfig::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('belong', __('Belong'));
        $show->field('recyclable_list', __('Recyclable list'));
        $show->field('active_list', __('Active list'));
        $show->field('recyclable', __('Recyclable'));
        $show->field('status', __('Status'));
        $show->field('enable_time', __('Enable time'));
        $show->field('disbale_time', __('Disbale time'));
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
        $form = new Form(new ResDistributionConfig());
        $form->hidden('belong', __('所属'));

        $form->radio('nationality', '分组展示')
            ->options([
                1 => '可循环的列表',
                2 => '使用中的列表',
                3 => '其他',
            ])->when(1, function (Form $form) {

                $form->keyValue('recyclable_list', __('可循环的列表'));

            })->when(2, function (Form $form) {

                $form->keyValue('active_list', __('使用中的列表'));

            })->when(3, function (Form $form) {

                $form->switch('recyclable', __('是否循环'));
                $form->switch('status', __('启用状态'));

            })->default(1);


        $form->ignore(['nationality',]);



//        $form->text('enable_time', __('Enable time'));
//        $form->text('disbale_time', __('Disbale time'));
//

        return $form;
    }
}
