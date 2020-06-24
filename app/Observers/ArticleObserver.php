<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\FileService;
use App\Services\ArticleService;

class ArticleObserver
{
    /**
     * 监听数据即将更新的事件。
     *
     * @param  Article $article
     * @return void
     */
    public function updating(Article $article)
    {
        (new FileService())->editFiles($article, $article->getOriginal('file_url'));
    }

    /**
     * 监听数据即将保存的事件。
     *
     * @param  Article $article
     * @return void
     */
    public function saving(Article $article)
    {
        (new ArticleService())->xssClena($article);
    }

    /**
     * 监听数据即将删除的事件。
     *
     * @param  Article $article
     * @return void
     */
    public function deleting(Article $article)
    {
        (new FileService())->delFiles($article);
    }
}
