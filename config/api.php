<?php

return [
    /*
     * 接口频率限制
     */
    'rate_limits' => [
        // 访问频率限制，次数/分钟
        'access' =>  env('RATE_LIMITS', '60,1'),
        // 登录相关，次数/分钟
        'sign' =>  env('SIGN_RATE_LIMITS', '10,1'),
    ],

    /**
     * 发送短信开关
     */
    'sms_code' => [
        'status' => 0,  //1：开启短信发送 0：关闭短信发送
        'code' => '123123'
    ],

    /**
     * 区分后台|前端传入图片
     */
    'type' => [
        'file_type' => 99, //数值为99 是前端出入
        'file_type_admin' => 2, //个人头像
    ]
];
