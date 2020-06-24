<?php

namespace App\Models;

class File extends Model
{
    protected $fillable = [
        'file_name', 'file_url', 'user_id', 'cate_id', 'sort', 'code', 'status', 'file_type', 'Keywords', 'describe'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'cate_id', 'id')->select('id', 'name');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select('id', 'name');
    }

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

    public function scopeRecentReplied($query)
    {
        // 按照更新时间排序
        return $query->orderBy('id', 'desc');
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }
}
