<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\ArticleService;
use App\Http\Queries\ArticleQuery;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\Api\ArticleRequest;

class ArticlesController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * 帖子列表
     * ---------------------------------------------------------------
     * @param array $query 模型数据
     *
     * @return array
     */
    public function index(Request $request, ArticleQuery $query)
    {
        //获取热门文章
        if ($request->hot && $request->hot == 1 && $request->top && $request->top == 1) {
            $query->where(['hot' => 1, 'top' => 1])
                ->orderBy('id', 'desc');
        }

        $articles = $query
            ->select($this->fieldset())
            ->orderBy('id', 'desc')
            ->paginate(config('setting.pagelist.page_index'));

        return ArticleResource::collection($articles);
    }


    /**
     * ---------------------------------------------------------------
     * 个人发布的帖子
     * ---------------------------------------------------------------
     * @param  User $user 模型
     * @param  ArticleQuery $query 返回数据
     *
     * @return array
     */
    public function userIndex(User $user, ArticleQuery $query)
    {
        $topics = $query->where('user_id', $user->id)
            ->select($this->fieldset())
            ->paginate(config('setting.pagelist.page_index'));

        return ArticleResource::collection($topics);
    }

    /**
     * ---------------------------------------------------------------
     * 详情
     * ---------------------------------------------------------------
     * @param int $articleId 数据ID
     * @param ArticleQuery $query 数据返回格式
     *
     * @return array
     */
    public function show($articleId, ArticleQuery $query)
    {
        $article = $query
            ->select($this->fieldset())
            ->findOrFail($articleId);

        return new ArticleResource($article);
    }

    /**
     * ---------------------------------------------------------------
     * 新增帖子
     * ---------------------------------------------------------------
     * @param ArticleRequest $request 数据返回格式
     * @param Article $article 模型数据
     *
     * @return json
     */
    public function store(ArticleRequest $request, Article $article, ArticleService $service)
    {
        $article = $service->addArticle($request);

        if (!$article) {
            abort(500, '创建帖子失败');
        }

        return response()->json([
            'message' => 'Successfully created article!'
        ], 201);
    }

    /**
     * ---------------------------------------------------------------
     * 更新帖子
     * ---------------------------------------------------------------
     * @param ArticleRequest $request 返回数据格式
     * @param Article $article 模型
     *
     * @return json
     */
    public function update(ArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);

        $article->update($request->all());

        return response()->json([
            'message' => 'Successfully update article!'
        ], 201);
    }

    /**
     * ---------------------------------------------------------------
     * 删除帖子
     * ---------------------------------------------------------------
     * @param Article $article 模型数据
     *
     * @return int 1
     */
    public function destroy(Article $article)
    {
        $this->authorize('destroy', $article);

        $article->delete();

        return response(null, 204);
    }

    /**
     * ---------------------------------------------------------------
     * 搜索帖子
     * ---------------------------------------------------------------
     * @param Request $request 数据
     *
     * @return array
     */
    public function searchArticle(ArticleQuery $query, Request $request)
    {
        if ($search = $request->input('keywords', '')) {
            $like = '%' . $search . '%';
            // 模糊搜索标题
            $query->where(function ($querys) use ($like) {
                $querys->where('article_name', 'like', $like);
            });
        }

        $articles = $query
            ->select($this->fieldset())
            ->get();

        return ArticleResource::collection($articles);
    }

    /**
     * ---------------------------------------------------------------
     * 查询字段
     * ---------------------------------------------------------------
     *
     * @return array
     */
    private function fieldset()
    {
        $field = [
            'id', 'article_name', 'code', 'file_url', 'Keywords', 'describe', 'content', 'status', 'hot', 'top', 'cate_id', 'user_id'
        ];
        return $field;
    }
}
