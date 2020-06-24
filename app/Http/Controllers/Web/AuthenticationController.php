<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthenticationController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * 获取第三方登录页面
     * ---------------------------------------------------------------
     * @param 	string 		$account 	登录方式 github|wechat|QQ
     * @return 	Resources        		返回首页
     *
     * @throws 	\Exception           	返回登录页
     */
    public function getSocialRedirect($account)
    {
        try {
            return Socialite::with($account)->redirect();
        } catch (\InvalidArgumentException $e) {
            return redirect('/');
        }
    }


    /**
     * ---------------------------------------------------------------
     * github登录回调
     * ---------------------------------------------------------------
     * @param 	string 		$account 	登录方式 github|wechat|QQ
     * @return 	Resources        		返回首页
     */
    public function getSocialCallback($account)
    {
        // 从第三方 OAuth 回调中获取用户信息
        $socialUser = Socialite::with($account)->user();

        // 在本地 users 表中查询该用户来判断是否已存在
        $user = User::where('provider_id', '=', $socialUser->id)
            ->where('provider', '=', $account)
            ->first();

        if ($user == null) {
            // 如果该用户不存在则将其保存到 users 表
            $newUser = new User();
            $newUser->name        = $socialUser->getName() ?? 0;
            $newUser->email       = $socialUser->getEmail() == '' ? '' : $socialUser->getEmail();
            $newUser->avatar      = $socialUser->getAvatar() ?? 0;
            $newUser->password    = '';
            $newUser->phone       = '';
            $newUser->provider    = $account ?? 0;
            $newUser->provider_id = $socialUser->getId();
            $newUser->save();
            $user = $newUser;
        }

        // 手动登录该用户
        Auth::login($user);

        // 登录成功后将用户重定向到首页
        return redirect('/');
    }
}
