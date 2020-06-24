<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Queries\ArticleQuery;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;

class CategoriesController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * 获取分类列表
     * ---------------------------------------------------------------
     * @param Http $request
     * @param string $categoryService 分类树形
     * @param string $code 分类标识
     *
     * @return array
     */
    public function index(Request $request, CategoryService $categoryService)
    {
        $code = $request->input('code');

        CategoryResource::wrap('data');

        if ($code) {
            return CategoryResource::collection($categoryService->getCategoryTree())
                ->when(
                    $code,
                    function ($query, $code) {
                        return $query->where('code', $code);
                    }
                );
        }

        return CategoryResource::collection($categoryService->getCategoryTree());
    }


    /**
     * ---------------------------------------------------------------
     * 获取分类下所有文字
     * ---------------------------------------------------------------
     * @param Request $request    分类ID
     * @param ArticleQuery $query 请求类
     *
     * @return array
     */
    public function cateArticels(Request $request,ArticleQuery $query)
    {
        $articles = $query->where('cate_id',$request->id)->get(['id','article_name','file_url']);

        return ArticleResource::collection($articles);
    }
}
