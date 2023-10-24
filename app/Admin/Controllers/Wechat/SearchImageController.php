<?php

namespace App\Admin\Controllers\Wechat;

use App\Models\WechatSearchImage;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SearchImageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '微信小程序-图片检索后台';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WechatSearchImage());

        $grid->column('id', __('编号'));
        $grid->column('keywords', __('关键词'));
        $grid->column('img_url', __('图片链接'))->image('',80,80);
        $grid->column('status', __('状态'));
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
        $show = new Show(WechatSearchImage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('keywords', __('Keywords'));
        $show->field('img_url', __('Img url'));
        $show->field('status', __('Status'));
        $show->field('updated_at', __('Updated at'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new WechatSearchImage());

        $form->text('keywords', __('Keywords'));
//        $form->text('img_url', __('Img url'));
        $form->image('img_url', __('图片上传'))
            ->move('/wechat_search_img', date('Ymd').rand(0,999).time().'.jpg')->removable()->help('请先压缩图片，以免影响程序加载 https://tinypng.com/');
        $form->switch('status', __('状态'))->default(1);

        return $form;
    }
}
