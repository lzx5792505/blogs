<?php

namespace App\Admin\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    /**
     * 搜索分类
     *
     * @param string $search 输入的关键字
     *
     * @return array $request 输出id对应分类名称
     */
    public function searchCategorys(Request $request)
    {
        $search = $request->input('q');
        $result = Category::query()
            ->where('name', 'like', '%' . $search . '%')
            ->paginate();

        $result->setCollection($result->getCollection()->map(function (Category $category) {
            return ['id' => $category->id, 'text' => $category->full_name];
        }));

        return $result;
    }
}
