<?php

namespace App\Http\Queries;

use App\Models\File;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class FileQuery extends QueryBuilder
{
    public function __construct()
    {
        parent::__construct(File::query()->where('status', 1));

        //加载模型对应关系
        $this->allowedIncludes('user', 'category')
            ->allowedFilters([
                'title',
                AllowedFilter::exact('cate_id'),
                //设置默认排序
                AllowedFilter::scope('withOrder')->default('recentReplied'),
            ]);
    }
}
