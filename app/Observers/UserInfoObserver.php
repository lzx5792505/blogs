<?php

namespace App\Observers;

use App\Models\UserInfo;
use App\Services\UserInfoService;

class UserInfoObserver
{

    /**
     * 监听数据即将保存的事件。
     *
     * @param  UserInfo $userInfo
     * @return void
     */
    public function saving(UserInfo $userInfo)
    {
        (new UserInfoService())->addFileUrl($userInfo);
    }
}
