<?php

namespace App\Http\Queries;

use App\Models\Article;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class ArticleQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(Article::query()->where('status', 1));

        $this->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('cate_id'),
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ]);
    }
}
