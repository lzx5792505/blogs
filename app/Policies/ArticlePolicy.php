<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Article;

class ArticlePolicy extends Policy
{
    /**
     * ---------------------------------------------------------------
     * 更新授权
     * ---------------------------------------------------------------
     * @param User $user   user模型
     * @param Article $article  article模型
     *
     * @return void
     */
    public function update(User $user, Article $article)
    {
        return $user->isAuthorOf($article);
    }

    /**
     * ---------------------------------------------------------------
     * 删除授权
     * ---------------------------------------------------------------
     * @param User $user   user模型
     * @param Article $article  article模型
     *
     * @return void
     */
    public function destroy(User $user, Article $article)
    {
        return $user->isAuthorOf($article);
    }
}
