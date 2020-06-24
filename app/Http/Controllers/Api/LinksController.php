<?php

namespace App\Http\Controllers\Api;

use App\Models\Link;
use App\Http\Resources\LinkResource;

class LinksController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * 获取缓存中的Link表数据
     * ---------------------------------------------------------------
     * @param Link $link  模型数据
     * 
     * @return array
     */
    public function index(Link $link)
    {
        $links = $link->getAllCached();

        return LinkResource::collection($links);
    }
}
