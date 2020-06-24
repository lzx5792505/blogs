<?php

namespace App\Admin\Controllers;

use App\Models\UserInfo;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\HasResourceActions;

class UserInfosController extends BaseController
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('用户列表（部分详情请点击ID）')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new UserInfo);
        //搜索条件
        $grid->quickSearch('username', 'email', 'phone','real_name','address');
        $grid->filter(function ($filter) {
            // 设置created_at字段的范围查询
            $filter->between('created_at', '注册日期')->datetime();
            // 去掉默认的ID搜索
            $filter->disableIdFilter();
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->id('ID')->sortable()->modal('详情', function ($model) {
            $comments = $model->where('id', $model->id)->get()->map(function ($comment) {
                return $comment->only(['real_name', 'birthday', 'address']);
            });
            return new Table(['真实姓名', '生日', '地址'], $comments->toArray());
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->file_url('头像')->image(45, 45);
        $grid->username('昵称');
        $grid->phone('电话');
        $grid->email('邮箱')->limit(30);
        $states = [
            'on'  => ['value' => 1, 'text' => '开启', 'color' => 'info'],
            'off' => ['value' => 2, 'text' => '禁用', 'color' => 'danger'],
        ];
        $grid->status('状态')->switch($states);
        // 不在页面显示 `新建` 按钮，因为我们不需要在后台新建用户资料
        $grid->disableCreateButton();
        // // 同时在每一行也不显示 `编辑` 按钮
        $grid->disableActions();
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        return $grid;
    }

    /**
     * 新增/编辑
     * @return Form
     */
    protected function form()
    {

        $form = new Form(new UserInfo);
        $states = [
            'on'  => ['value' => 1, 'text' => '开启', 'color' => 'info'],
            'off' => ['value' => 2, 'text' => '禁用', 'color' => 'danger'],
        ];
        $form->switch('status', '启用')->states($states);
        return $form;
    }
}
