<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\HasResourceActions;

class CategorysController extends BaseController
{
    use HasResourceActions;

    public function index(Content $content)
    {
        return $content
            ->header('分类管理列表')
            ->body($this->grid());
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header('分类管理编辑')
            ->body($this->form(true)->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header('分类管理添加')
            ->body($this->form(false));
    }

    /**
     * 列表
     *
     * @return grid
     */
    protected function grid()
    {
        $grid = new Grid(new Category);
        $grid->quickSearch('name', 'code');
        $grid->filter(function ($filter) {
            $filter->between('created_at', '创建日期')->datetime();
            $filter->disableIdFilter();
        });
        $grid->model()->orderBy('sort', 'desc')->orderBy('id', 'desc');
        $grid->id('ID')->sortable();
        $grid->sort('排序')->editable();
        $grid->name('名称');
        $grid->code('标识');
        $grid->level('层级');
        $grid->path('路径');
        $grid->describe('描述')->display(function ($title) {
            return "<span style='color:blue'> $title </span>";
        });
        $grid->actions(function ($actions) {
            $actions->disableView();
        });
        return $grid;
    }

    /**
     * 新增|编辑
     *
     * @return Form
     */
    protected function form($isEditing = false)
    {
        $form = new Form(new Category());
        $form->text('name', '名称')->rules('required');
        $form->text('code', '标识')->rules('required');
        $form->text('sort', '排序');
        if ($isEditing) {
            $form->display('is_directory', '是否目录')->with(function ($value) {
                return $value ? '是' : '否';
            });
            $form->display('parent.name', '父类目');
        } else {
            $form->radio('is_directory', '是否目录')
                ->options(['1' => '是', '0' => '否'])
                ->default('0')
                ->rules('required');
            $form->select('parent_id', '父类目')->ajax('/blogs/api/categorys');
        }
        $form->quill('describe', '描述');
        return $form;
    }
}
