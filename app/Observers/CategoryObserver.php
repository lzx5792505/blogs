<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CategoryService;

class CategoryObserver
{
    /**
     * 监听数据即将创建的事件。
     *
     * @param  Category $category
     * @return void
     */
    public function creating(Category $category)
    {
        (new CategoryService())->getPath($category);
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param  Category $category
     * @return void
     */
    public function saving(Category $category)
    {
        (new CategoryService())->addNull($category);
    }
}
