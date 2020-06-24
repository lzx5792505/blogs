<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use App\Models\UserInfo;
use App\Models\File;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\HasResourceActions;

class FilesController extends BaseController
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('资源管理列表（部分详情请点击ID）')
            ->body($this->grid());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('资源管理编辑')
            ->body($this->form(true)->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('资源管理添加')
            ->body($this->form(false));
    }

    /**
     * 列表
     *
     * @return grid
     */
    protected function grid()
    {
        $grid = new Grid(new File());
        $grid->quickSearch('file_name', 'code');
        $grid->filter(function ($filter) {
            $filter->between('created_at', '创建日期')->datetime();
            $filter->disableIdFilter();
        });
        $grid->id('ID')->sortable()->modal('详情', function ($model) {
            $comments = $model->where('id', $model->id)->get()->map(function ($comment) {
                return $comment->only(['code', 'Keywords', 'describe', 'content']);
            });
            return new Table(['标识', '关键词', '描述', '内容'], $comments->toArray());
        });
        $grid->model()->orderBy('id', 'desc');
        $grid->sort('排序')->editable();
        $grid->file_url('图片')->lightbox([env('APP_URL') . '/' . 'uploads' . '/', 'width' => 48, 'height' => 48]);
        $grid->file_name('名称');
        $grid->user_id('用户')->display(function ($create_by) {
            return UserInfo::find($create_by)['username'] ?? '';
        });
        $grid->cate_id('分类')->display(function ($create_by) {
            return Category::find($create_by)['name'] ?? '';
        });
        $grid->code('标识');
        $grid->file_type('类型')->using([
            1 => '文章',
            2 => '头像',
            3 => '公告',
            4 => '轮播',
        ], '未分级')->dot([
            1 => 'success',
            2 => 'danger',
            3 => 'success',
            4 => 'danger',
        ], 'warning');
        $grid->created_at('创建时间');
        $states = [
            'on'  => ['value' => 1, 'text' => '上架', 'color' => 'info'],
            'off' => ['value' => 2, 'text' => '下架', 'color' => 'danger'],
        ];
        $grid->status('状态')->switch($states);
        return $grid;
    }

    /**
     * 新增|编辑
     *
     * @return Form
     */
    protected function form($isEditing = false)
    {
        $form = new Form(new File());
        $form->chunk_file('file_url', '图片');
        $form->text('file_name', '名称')->rules('required');
        $form->text('code', '标识')->rules('required');
        $form->text('sort', '排序');
        if ($isEditing) {
            $form->select('cate_id', '所属分类')->options(function ($id) {
                $category = Category::find($id);
                if ($category) {
                    return [$category->id => $category->full_name];
                }
            })->ajax('/blogs/api/categorys');
        } else {
            $form->select('cate_id', '所属分类')->ajax('/blogs/api/categorys');
        }
        $form->radio('file_type', '所属类型')->options([
            1 => '文章',
            2 => '头像',
            3 => '公告',
            4 => '轮播',
        ])->default(1);
        $states = [
            'on'  => ['value' => 1, 'text' => '上架', 'color' => 'info'],
            'off' => ['value' => 2, 'text' => '下架', 'color' => 'danger'],
        ];
        $form->switch('status', '上架')->states($states);
        $form->quill('describe', '描述');
        return $form;
    }
}
