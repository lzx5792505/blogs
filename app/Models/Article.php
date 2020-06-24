<?php

namespace App\Models;

class Article extends Model
{
    //白名单
    protected $fillable = [
        'article_name', 'cate_id', 'user_id', 'sort', 'models', 'status', 'cover', 'Keywords', 'describe', 'content',
    ];

    //关联分类
    public function category()
    {
        return $this->belongsTo(Category::class, 'cate_id', 'id')->select('id', 'name');
    }

    //关联用户
    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'name');
    }

    //关联最后评论用户
    public function users()
    {
        return $this->belongsTo(User::class, 'last_reply_user_id')->select('id', 'name');
    }

    //排序方法
    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
    }

    //id排序
    public function scopeRecentReplied($query)
    {
        // 按照更新时间排序
        return $query->orderBy('id', 'desc');
    }

    //创建时间排序
    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }

    //展示友情链接
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }
}
