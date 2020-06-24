<?php

namespace App\Observers;

use Cache;
use App\Models\Link;

class LinkObserver
{
    /**
     * 监听数据保存后的事件。
     *
     * @param  Link $link
     * @return void
     */
    public function saved(Link $link)
    {
        Cache::forget($link->cache_key);
    }
}
