<?php

namespace App\Admin\Controllers\CustomerService;

use App\Models\CustomerService\Config;
use App\Models\CustomerService\Record;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecordController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '客服系统聊天记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Record());
        Admin::js('/assets/admin/customer_service/mSlider.js');
        Admin::js('/assets/admin/customer_service/jquery.base64.js');
        Admin::js('/assets/admin/customer_service/mslider_config.js');
        //侧边栏的和聊天框的代码和引入
        Admin::css('/assets/admin/customer_service/reset.min.css');
        Admin::css('/assets/admin/customer_service/font-awesome.min.css');
        Admin::css('/assets/admin/customer_service/style.css');
        Admin::js('/assets/admin/customer_service/list.min.js');
        Admin::js('/assets/admin/customer_service/handlebars.min.js');
        Admin::js('/assets/admin/customer_service/script.js');
        Admin::style('.mSlider-inner {overflow:auto;}');
        Admin::html('<div class="wrap" id="slider_message_div" style="margin-top: 0px;">留言内容加载中</div>');
        $grid->column('id', __('编号'));

        $grid->column('config_id_second', __('账号'))->display(function (){
            return $this->configData->account;
        });
        $grid->column('config_id', __('账号信息'))->display(function (){
            return $this->configData->custom_name;
        });

        $grid->column('customer_styleName', __('风格名称（用于同步）'))->width(200);
        $grid->column('customer_mobile', __('手机号'));
        $grid->column('customer_kw', __('关键字'));
        $grid->column('customer_se', __('搜索引擎'));
        $grid->column('customer_remark', __('备注'));
        $grid->column('customer_weixin', __('微信'));
        $grid->column('syn_status', __('是否同步'))->bool(['1' => true, '0' => false]);
        $grid->column('data_guest_id', __('聊天详情'))->display(function ($data_guest_id){
            $customer_arr = json_decode($this,true);
            return '<button type="button" class="btn btn-secondary" data-whole_data="'.base64_encode(json_encode($customer_arr)).'">详细信息</button>';
        });
        $grid->column('updated_at', __('上次更新时间'))->display(function ($updated_at){
            return date('Y-m-d H:i:s',strtotime($updated_at));
        });


        $grid->model()->orderBy('updated_at', 'desc');

        $grid->disableCreateButton();
        $grid->disableActions();
        $grid->actions(function ($actions){
            // 去掉删除
            $actions->disableDelete();
            // 去掉编辑
            $actions->disableEdit();
            // 去掉查看
            $actions->disableView();

        });
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->expand();//默认展开搜索栏
            $filter->column(5/10, function ($filter) {
                $filter->like('customer_mobile', '手机号');
                $filter->like('customer_remark', '备注');
                $filter->like('customer_se', '搜索引擎');
                $filter->like('customer_kw', '关键词');
                $filter->like('customer_styleName', '风格');
            });
            $filter->column(5/10, function ($filter) {
                $user_obj = Auth::guard('admin')->user();
                if ($user_obj->id == 1){
                    //超级管理员

                    $config_data = Config::get()->toarray();
                }else{
                    $config_data = Config::get()->toarray();
                }
                $config_arr = [];
                foreach ($config_data as $config_data){
                    $config_arr[$config_data['id']] = '账号（'.$config_data['id'].'）：'.$config_data['custom_name'];
                }

                $filter->in('config_id', '账户信息')->multipleSelect($config_arr);

                $filter->in('syn_status', '同步状态')->checkbox([
                    '0'    => '未同步',
                    '1'    => '已同步',
                ]);
                $filter->where(function ($query) {
                    switch ($this->input) {
                        case 'all':
                            //全部数据的展示
                            break;
                        case 'user':
                            $query->whereNotNull('customer_mobile');
                            break;
                        case 'message':
                            $query->whereNull('customer_mobile');
                            break;
                        default:
                            //啥操作也不做，全部展示出来
                    }
                }, '数据的分类', 'mobile_status')->radio([
                    'all' => '全部数据',
                    'user' => '仅展示用户数据',
                    'message' => '仅展示留言数据',
                ]);
                $filter->between('updated_at', '上次更新时间')->datetime();
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
        $show = new Show(Record::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('config_id', __('Config id'));
        $show->field('data_guest_id', __('Data guest id'));
        $show->field('data_session', __('Data session'));
        $show->field('data_end', __('Data end'));
        $show->field('data_message', __('Data message'));
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
        $form = new Form(new Record());

        $form->number('config_id', __('Config id'));
        $form->text('data_guest_id', __('Data guest id'));
        $form->text('data_session', __('Data session'));
        $form->text('data_end', __('Data end'));
        $form->text('data_message', __('Data message'));

        return $form;
    }
}
