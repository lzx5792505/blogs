<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use App\Http\Requests\Api\SmsCodeRequest;
use Illuminate\Auth\AuthenticationException;

class SmsCodesController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * 手机验证码
     * ---------------------------------------------------------------
     * @param 	array $request  验证后的数据
     * @param   array $easySms  验证短信配置
     * 
     * @return 	json|string     返回缓存的验证码
     */
    public function store(SmsCodeRequest $request, EasySms $easySms)
    {
        $captchaData = \Cache::get($request->captcha_key);

        if (!$captchaData) {
            abort(403, '图片验证码已失效');
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证错误就清除缓存
            \Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }

        $phone = $captchaData['phone'];

        // 生成6位随机数，左侧补0
        $code = str_pad(random_int(1, 9999), 6, 0, STR_PAD_LEFT);

        try {
            //获取配置 是否发送短信
            if (config('api.sms_code.status') == 1) {
                $result = $easySms->send($phone, [
                    'template' => config('easysms.gateways.aliyun.templates.register'),
                    'data' => [
                        'code' => $code
                    ],
                ]);
            } else {
                //默认短信code
                $code = config('api.sms_code.code');
            }
        } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
            $message = $exception->getException('aliyun')->getMessage();
            abort(500, $message ?: '短信发送异常');
        }

        $key = 'smsCodes_key_' . Str::random(15);
        $expiredAt = now()->addMinutes(10);
        // 缓存验证码 5分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);
        // 清除图片验证码缓存
        \Cache::forget($request->captcha_key);

        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
