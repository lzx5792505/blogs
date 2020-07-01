<?php

return [
    /*
     * Passport 验证
     */
    'oauth' => [
        // 客户端id
        'client_id'     => 1,
        // 客户端秘钥
        'client_secret' => 'AJb8wVOGA47cu4OYSw4V9AcjMk2nTN9KNVUd4UUi',
        // 验证类型
        'grant_type'    => 'password',
        //作用域
        'scope'         => '*'
    ],

    /*
     * Passport user 验证
     */
    'oauthUser' => [
        // 客户端id
        'client_id'     =>  0,
        // 客户端秘钥
        'client_secret' => 0,
        // 验证类型
        'grant_type'    => 0,
        //作用域
        'scope'        => 0
    ],

    /*
     * 分页
     */
    'pagelist' => [
        'page_index'     =>  6,
    ],
];
