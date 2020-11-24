<?php

namespace App\Admin\Controllers\Email;

use App\Models\EmailData;
use App\Models\EmailConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class EmailDataController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '邮件列表';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EmailData);
        $user_obj = Auth::guard('admin')->user();

        $grid->header(function ($query) {
            $user_obj = Auth::guard('admin')->user();

            return '用户ID：'.$user_obj->id;
        });

        $grid->column('id', __('ID'));
        $grid->column('username', __('用户名'));
        $grid->column('phone', __('手机'));
        $grid->column('from', __('来源'))->width(200);
        $grid->column('title', __('内容标题'));
        $grid->column('data_date', __('数据日期'));
        $grid->column('from_mail', __('发件人'));
        $grid->column('mail_title', __('邮件标题'));
        $grid->column('mail_date', __('邮件日期'));
        $grid->column('mail_content', __('邮件内容'));
        $grid->column('econfig_id', __('Econfig id'));


        $grid->column('created_at', __('入库时间'))->sortable();
        $grid->column('user_id', __('用户ID'));



        if ($user_obj->id == 1) {
            // code...

        } else {
            $grid->model()->whereIn('user_id', [$user_obj->id]);
        }

        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            $filter->disableIdFilter();



            $filter->column(1/2, function ($filter) {
                $filter->like('phone', '手机号');

                $filter->between('created_at', '时间区间')->datetime();

            });

            $filter->column(1/2, function ($filter) {
                // 右半边
                $user_obj = Auth::guard('admin')->user();
                $email_config = EmailConfig::get()->where('user_id',$user_obj->id)->toarray();
                $econfig_arr = [];
                foreach ($email_config as $key=>$config_data){
                    // var_dump($config_data);
                    $econfig_arr[$config_data['id']] = '编号：'.$config_data['id'].'；账号:'.$config_data['email_address'];
                }
                $filter->in('econfig_id', '账号')->multipleSelect($econfig_arr);


            });

        });
        $grid->expandFilter();
        $grid->model()->orderBy('created_at', 'desc');

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
        $show = new Show(EmailData::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('username', __('Username'));
        $show->field('phone', __('Phone'));
        $show->field('from', __('From'));
        $show->field('title', __('Title'));
        $show->field('data_date', __('Data date'));
        $show->field('from_mail', __('From mail'));
        $show->field('mail_title', __('Mail title'));
        $show->field('mail_date', __('Mail date'));
        $show->field('mail_content', __('Mail content'));
        $show->field('econfig_id', __('Econfig id'));
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
        $user_obj = Auth::guard('admin')->user();
        $form = new Form(new EmailData());

        $form->text('username', __('Username'));
        $form->mobile('phone', __('Phone'));
        $form->text('from', __('From'));
        $form->text('title', __('Title'));
        $form->datetime('data_date', __('Data date'))->default(date('Y-m-d H:i:s'));
        $form->text('from_mail', __('From mail'));
        $form->text('mail_title', __('Mail title'));
        $form->datetime('mail_date', __('Mail date'))->default(date('Y-m-d H:i:s'));
        $form->textarea('mail_content', __('Mail content'));
        $form->number('econfig_id', __('Econfig id'));
        $form->number('user_id', __('User id'))->default($user_obj->id);

        return $form;
    }
}
