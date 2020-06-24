<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    /**
     * 公用
     */
    $router->get('api/categorys', 'SearchController@searchCategorys');

    /**
     * 用户路由
     */
    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->get('users', 'UsersController@index');
    $router->put('users/{id}', 'UsersController@update');

     /**
     * 个人信息路由
     */
    $router->get('userInfos', 'UserInfosController@index')->name('admin.userInfos.index');
    $router->post('userInfos', 'UserInfosController@store');
    $router->get('userInfos/create', 'UserInfosController@create');
    $router->get('userInfos/{id}/edit', 'UserInfosController@edit');
    $router->get('userInfos/{id}', 'UserInfosController@show');
    $router->put('userInfos/{id}', 'UserInfosController@update');
    $router->delete('userInfos/{id}', 'UserInfosController@destroy');

    /**
     * 分类路由
     */
    $router->get('cates', 'CategorysController@index')->name('admin.cates.index');
    $router->post('cates', 'CategorysController@store');
    $router->get('cates/create', 'CategorysController@create');
    $router->get('cates/{id}/edit', 'CategorysController@edit');
    $router->get('cates/{id}', 'CategorysController@show');
    $router->put('cates/{id}', 'CategorysController@update');
    $router->delete('cates/{id}', 'CategorysController@destroy');

    /**
     * 资源路由
     */
    $router->get('files', 'FilesController@index')->name('admin.files.index');
    $router->post('files', 'FilesController@store');
    $router->get('files/create', 'FilesController@create');
    $router->get('files/{id}/edit', 'FilesController@edit');
    $router->get('files/{id}', 'FilesController@show');
    $router->put('files/{id}', 'FilesController@update');
    $router->delete('files/{id}', 'FilesController@destroy');

    /**
     * 文章路由
     */
    $router->get('articles', 'ArticlesController@index')->name('admin.articles.index');
    $router->post('articles', 'ArticlesController@store');
    $router->get('articles/create', 'ArticlesController@create');
    $router->get('articles/{id}/edit', 'ArticlesController@edit');
    $router->get('articles/{id}', 'ArticlesController@show');
    $router->put('articles/{id}', 'ArticlesController@update');
    $router->delete('articles/{id}', 'ArticlesController@destroy');
});
