<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public $error_msg = 'error';
    public $success_msg = 'success';

    /**
     * 设置id倒叙
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('id', 'desc');
    }

    /**
     * 设置排序 倒叙
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort', 'desc');
    }
}
