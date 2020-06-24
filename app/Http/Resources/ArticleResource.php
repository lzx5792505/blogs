<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = parent::toArray($request);
        //返回作者
        $data['user'] = new UserResource($this->whenLoaded('user'));
        //返回分类
        $data['category'] = new CategoryResource($this->whenLoaded('category'));

        return $data;
    }
}
