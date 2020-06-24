<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'phone', 'email', 'avatar',
        'notification_count', 'last_actived_at',  'status', 'registration_id'
    ];

    protected $hidden = [
        'password', 'remember_token', 'weixin_openid', 'weixin_unionid', 'provider', 'provider_id'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * 获取对应model的user_id
     */
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    /**
     * 消息通知
     */
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }


    /**
     * 注册密码加密
     *
     * @param string $value  用户输入的密码
     *
     * @return string $value 加密后的密码
     */
    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {
            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }

    /**
     * Passport 认证 手机或者邮箱登录
     *
     * @param string $username 登录名称
     *
     * @return array 用户信息
     */
    public function findForPassport($username)
    {
        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        return  self::where($credentials)->first();
    }

    /**
     * PassPort 认证 密码认证
     *
     * @param string $password 用户输入的密码
     *
     * @return boole
     */
    public function validateForPassportPasswordGrant($password)
    {
        //如果请求密码等于数据库密码 返回true（此为实例，根据自己需求更改）
        if (self::verifyPassword($password) == $this->password) {
            return true;
        }
        return false;
    }

    /**
     * 确认密码是否正确
     *
     * @param string $password
     *
     * @return bool
     */
    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
