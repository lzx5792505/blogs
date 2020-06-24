<?php

use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| 获取首页
|-------------------------------------------------------------------------------
| 请求URL:     	/
| 控制器方法:   Web\AppController@index
| 请求方式:     GET
| 功能描述:     获取首页
*/
Route::get('/', 'Web\AppController@index');

/*
|-------------------------------------------------------------------------------
| 获取登录页
|-------------------------------------------------------------------------------
| 请求URL:     	/login
| 控制器方法:   Web\AppController@doLogin
| 请求方式:     GET
| 功能描述:     获取登录页面
*/
Route::get('/login', 'Web\AppController@doLogin' )->name('login');

/*
|-------------------------------------------------------------------------------
| 第三方登录
|-------------------------------------------------------------------------------
| 请求URL:     	/auth/{social}
| 控制器方法:   Web\AuthenticationController@getSocialRedirect
| 请求方式:     GET
| 功能描述:     第三方登录
*/
Route::get( '/auth/{social}', 'Web\AuthenticationController@getSocialRedirect' );

/*
|-------------------------------------------------------------------------------
| 第三方登录回调
|-------------------------------------------------------------------------------
| 请求URL:     	/auth/{social}/callback
| 控制器方法:   Web\AuthenticationController@getSocialCallback
| 请求方式:     GET
| 功能描述:     第三方登录回调并登录app
*/
Route::get( '/auth/{social}/callback', 'Web\AuthenticationController@getSocialCallback' );

