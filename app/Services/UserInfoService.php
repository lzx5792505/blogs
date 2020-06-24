<?php

namespace App\Services;

class UserInfoService extends BaseService
{

    /**
     * ---------------------------------------------------------------
     * 用户头像为空，添加默认头像
     * ---------------------------------------------------------------
     * @param User $model
     *
     * @return string
     */
    public function addFileUrl($userInfo)
    {
        if (empty($userInfo->file_url)) {
            $userInfo->file_url = env('APP_URL') . '/' . 'uploads' . '/' . 'sos.jpg';
        }
    }

}
