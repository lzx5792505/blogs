<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Models\UserInfo;
use App\Models\Category;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Widgets\Table;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\HasResourceActions;

class ArticlesController extends BaseController
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('文章管理列表（部分详情请点击ID）')
            ->body($this->grid());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('文章管理编辑')
            ->body($this->form(true)->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('文章管理添加')
            ->body($this->form(false));
    }

    /**
     * 列表
     *
     * @return grid
     */
    protected function grid()
    {
        $grid = new Grid(new Article());
        $grid->quickSearch('article_name', 'describe', 'Keywords');
        $grid->filter(function ($filter) {
            $filter->between('created_at', '创建日期')->datetime();
            $filter->equal('models', '来源')->select([1 => '新闻动态', 2 => '公告', 3 => '法规说明']);
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
        $grid->article_name('名称');
        $grid->user_id('用户')->display(function ($create_by) {
            return UserInfo::find($create_by)['username'] ?? '';
        });
        $grid->cate_id('分类')->display(function ($create_by) {
            return Category::find($create_by)['name'] ?? '';
        });
        $grid->Keywords('关键词');
        $grid->models('来源')->using([
            1 => '新闻动态',
            2 => '公告',
            3 => '法规说明',
        ], '未分级')->dot([
            1 => 'success',
            2 => 'danger',
            3 => 'success',
        ], 'warning');
        $grid->hot('热门')->using([
            1 => '是',
            2 => '否',
        ], '未分级')->dot([
            1 => 'success',
            2 => 'danger',
        ], 'warning');
        $grid->top('推荐')->using([
            1 => '是',
            2 => '否',
        ], '未分级')->dot([
            1 => 'success',
            2 => 'danger',
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
        $form = new Form(new Article());
        $form->chunk_file('file_url', '图片');
        $form->text('article_name', '名称')->rules('required');
        $form->text('code', '标识')->rules('required');
        $form->text('sort', '排序');
        if ($isEditing) {
            $form->select('cate_id', '分类')->options(function ($id) {
                $category = Category::find($id);
                if ($category) {
                    return [$category->id => $category->full_name];
                }
            })->ajax('/blogs/api/categorys');
        } else {
            $form->select('cate_id', '分类')->ajax('/blogs/api/categorys');
        }
        $form->radio('models', '类型')->options([
            1 => '新闻动态',
            2 => '公告',
            3 => '法规说明'
        ])->default(1);
        $form->radio('hot', '热门')->options([
            1 => '是',
            2 => '否',
        ])->default(2);
        $form->radio('top', '推荐')->options([
            1 => '是',
            2 => '否',
        ])->default(2);
        $states = [
            'on'  => ['value' => 1, 'text' => '上架', 'color' => 'info'],
            'off' => ['value' => 2, 'text' => '下架', 'color' => 'danger'],
        ];
        $form->switch('status', '上架')->states($states);
        $form->text('Keywords', '关键词')->rules('required');
        $form->text('describe', '描述');
        $form->quill('content', '内容')->rules('required');
        return $form;
    }
}
