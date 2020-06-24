<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function rules()
    {
        //article 文章  avatar 头像
        $rules = [
            'code' => 'required|string|in:avatar,article',
        ];

        //头像标识
        if ($this->code == 'avatar') {
            $rules['file'] = 'required|mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200';
        } else {
            $rules['file'] = 'required|mimes:jpeg,bmp,png,gif';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'file.dimensions' => '图片的清晰度不够，宽和高需要 200px 以上',
        ];
    }
}
