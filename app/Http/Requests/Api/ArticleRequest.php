<?php

namespace App\Http\Requests\Api;

class ArticleRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'article_name' => 'required|string',
                    'content' => 'required|string',
                    'cate_id' => 'required|exists:categories,id',
                ];
                break;
            case 'PATCH':
                return [
                    'article_name' => 'string',
                    'content' => 'string',
                    'cate_id' => 'exists:categories,id',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'article_name' => '标题',
            'content' => '话题内容',
            'cate_id' => '分类',
        ];
    }
}
