<?php

namespace App\Services;

use App\Models\User;

class UserService extends BaseService
{

    /**
     * ---------------------------------------------------------------
     * 用户头像为空，添加默认头像
     * ---------------------------------------------------------------
     * @param User $model
     *
     * @return string
     */
    public function addAvatar($user)
    {
        if (empty($user->avatar)) {
            $user->avatar = env('APP_URL') . '/' . 'uploads' . '/' . 'sos.jpg';
        }
    }


    /**
     * ---------------------------------------------------------------
     * 注册用户
     * ---------------------------------------------------------------
     * @param array $request 验证的数据
     * @param string $smsCodes 缓存的用户电话
     *
     * @return boole
     */
    public function createUser($request, $smsCodes)
    {
        $user = new User();
        //创建用户
        $user->name = $request->name;
        $user->phone = $smsCodes;
        $user->password = $request->password;

        if ($user->save()) {
            return true;
        }
        return false;
    }

    /**
     * ---------------------------------------------------------------
     * 更新用户
     * ---------------------------------------------------------------
     * @param array $request
     *
     * @return array
     */
    public function editUser($request, $uploader)
    {
        $attributes = $request->only(['name', 'email', 'phone']);

        if (self::phone($request->phone) == false) {
            abort(500, '重复的手机号码，请重新输入！');
        }

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 416);
            if ($result) {
                $data['avatar'] = $result['file_url'];
            }
        }

        return $attributes;
    }

    /**
     * ---------------------------------------------------------------
     * 验证手机是否重复
     * ---------------------------------------------------------------
     * @param string $phone 手机号码
     *
     * @return boole
     */
    public function  phone($phone)
    {
        $result = User::where('phone', $phone)->value('phone');

        if ($result == $phone) {
            return false;
        }

        return true;
    }
}
