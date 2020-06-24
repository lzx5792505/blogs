<?php

namespace App\Services;

use App\Models\Category;

class CategoryService extends BaseService
{
    /**
     * ---------------------------------------------------------------
     * 格式化分类
     * ---------------------------------------------------------------
     * @param int $parentId 父类ID
     * @param Category $allCategories 所有分类集合
     *
     * @return array $data
     */
    public function getCategoryTree($parentId = null, $allCategories = null)
    {
        if (is_null($allCategories)) {
            $allCategories = Category::orderBy('sort', 'desc')->get();
        }

        return $allCategories
            ->where('parent_id', $parentId)
            ->map(function (Category $category) use ($allCategories) {
                $data = [
                    'id'        => $category->id,
                    'name'      => $category->name,
                    'parent_id' => $category->parent_id,
                    'level'     => $category->level,
                    'code'      => $category->code,
                    'path'      => $category->path,
                    'sort'      => $category->sort
                ];

                if (!$category->is_directory) {
                    return $data;
                }

                $data['children'] = $this->getCategoryTree($category->id, $allCategories);

                return $data;
            });
    }

    /**
     * ---------------------------------------------------------------
     * 分类默认添加层级与path
     * ---------------------------------------------------------------
     * @param Category $category 分类模型
     *
     * @return string|boole  parent_id & path
     */
    public function getPath($category)
    {
        if (!$category->parent_id) {
            $category->level = 0;

            $category->path  = '0' . '-';
        } else {
            $category->level = $category->parent->level + 1;

            $category->path  = $category->parent->path . $category->parent_id . '-';
        }
    }

    /**
     * ---------------------------------------------------------------
     * 数据库不为空的值添加默认值
     * ---------------------------------------------------------------
     * @param Category $category 分类模型
     *
     * @return int  parent_id&sort
     */
    public function addNull($category)
    {
        if (empty($category->parent_id)) {
            $category->parent_id = 0;
        }

        if (empty($category->sort)) {
            $category->sort = 0;
        }
    }
}
