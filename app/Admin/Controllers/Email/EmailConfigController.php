<?php

namespace App\Admin\Controllers\Email;

use App\Models\EmailConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;

class EmailConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '邮箱配置';


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new EmailConfig);

        $grid->column('id', __('Id'))->display(function ($name) {
		    return "$name";
		});
        $grid->column('user_id', __('User id'));
        $grid->column('email_address', __('Email address'));
        $grid->column('email_password', __('Email password'));
        $grid->column('host_port', __('Host port'));
        $grid->column('status', __('状态'));
//        $grid->column('move_folder', __('move folder'))->editable('select',function($single_data){
//        	//
//        	if (empty($single_data->move_folder)) {
//        		//迁移目录为空  去查询相关的邮箱目录进行读取
//        		$email_config = new EmailConfig;
//        		$res_mail_folder_list = $email_config->get_mail_folder_list($single_data);
//        		return $res_mail_folder_list;
//        	} else {
//        		// //迁移目录不为空 将已读取的邮箱目录展示出来
//
//        		return ["$single_data->move_folder"=>$single_data->move_folder];
//        	}
//
//		} );

        $grid->column('create_date', __('Create date'));
        // $grid->username('用户名');

		$grid->filter(function($filter){

		    // 去掉默认的id过滤器
		    $filter->disableIdFilter();

		    // 在这里添加字段过滤器
		    // $filter->like('name', 'name');


		});
		$user_obj = Auth::guard('admin')->user();
		if ($user_obj->id == 1) {
			// code...
		} else {
			$grid->model()->whereIn('user_id', [$user_obj->id]);
		}


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
        $show = new Show(EmailConfig::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('email_address', __('Email address'));
        $show->field('email_password', __('Email password'));
        $show->field('host_port', __('Host port'));
        $show->field('move_folder', __('move folder'));
        $show->field('create_date', __('Create date'));


        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
    	$user_arr = Auth::guard('admin')->user()->toarray();//当前登录用户的id

        $form = new Form(new EmailConfig);

        $form->number('user_id', __('User id'))->default($user_arr['id']);
        $form->number('status', __('状态'));
        $form->text('email_address', __('Email address'));
        $form->text('email_password', __('Email password'));
        $form->text('host_port', __('Host port'));
        $form->text('move_folder', __('move folder'));
        $form->datetime('create_date', __('Create date'))->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
