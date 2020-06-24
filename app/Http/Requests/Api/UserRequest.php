<?php

namespace App\Http\Requests\Api;

class UserRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => [
                        'required',
                        'regex:/^([\x{4E00}-\x{9FA5}\x{f900}-\x{fa2d}]|[a-zA-Z])([\x{4E00}-\x{9FA5}\x{f900}-\x{fa2d}]|[a-zA-Z0-9_-]){1,20}$/u',
                        'between:3,25',
                        'unique:users,name'
                    ],
                    'password' => 'required|alpha_dash|min:6',
                    'smsCodes_key' => 'required|string',
                    'smsCodes_code' => 'required|string',
                ];
                break;
            case 'PATCH':
                $userId = auth('api')->id();

                return [
                    'name' => [
                        'between:3,25',
                        'regex:/^([\x{4E00}-\x{9FA5}\x{f900}-\x{fa2d}]|[a-zA-Z])([\x{4E00}-\x{9FA5}\x{f900}-\x{fa2d}]|[a-zA-Z0-9_-]){1,20}$/u',
                        'unique:users,name,' . $userId,
                    ],
                    'email' => 'email|unique:users,email,' . $userId,
                    'phone' => [
                        'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/',
                        'unique:users,phone,' . $userId,
                    ],
                    'avatar' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=198,min_height=198',
                ];
                break;
        }
    }

    public function attributes()
    {
        return [
            'smsCodes_key' => '短信验证码 key',
            'smsCodes_code' => '短信验证码',
        ];
    }

    public function messages()
    {
        return [
            'name.unique'   => '用户名已被占用，请重新填写。',
            'name.regex'    => '用户名只支持英文、数字、横杆和下划线。',
            'name.between'  => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
        ];
    }
}
