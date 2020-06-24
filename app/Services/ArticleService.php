<?php

namespace App\Services;

use App\Models\Article;

class ArticleService extends BaseService
{
    /**
     * ---------------------------------------------------------------
     * 新增帖子
     * ---------------------------------------------------------------
     * @param array $request 验证后的数据
     *
     * @return boole
     */
    public function addArticle($request)
    {
        $article = new Article();

        $article->fill($request->all());

        $article->user_id = $request->user()->id;

        if ($article->save()) {
            return true;
        }
        return false;
    }

    /**
     * ---------------------------------------------------------------
     * 过滤XSS  数据库不为空的值添加默认值
     * ---------------------------------------------------------------
     * @param Article $articles 传入的模型数据
     *
     * @return string
     */
    public function xssClena($articles)
    {
        parent::addNull($articles);

        $articles->content = clean($articles->content, 'user_article_content');

        if (empty($articles->describe)) {
            $articles->describe = make_excerpt($articles->content);
        }
    }
}
