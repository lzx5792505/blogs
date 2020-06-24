<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {
        /**
         *
         * 登录相关路由
         *
         */
        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                // 图片验证码
                Route::post('captchas', 'CaptchasController@store')
                    ->name('captchas.store');

                // 短信验证码
                Route::post('smsCodes', 'SmsCodesController@store')
                    ->name('smsCodes.store');

                // 用户注册
                Route::post('users', 'UsersController@store')
                    ->name('users.store');

                // 第三方登录
                Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
                    ->where('social_type', 'weixin')
                    ->name('socials.authorizations.store');

                // 登录
                Route::post('authorizations', 'AuthorizationsController@store')
                    ->name('api.authorizations.store');

                // 刷新token
                Route::put('authorizations/current', 'AuthorizationsController@update')
                    ->name('authorizations.update');

                // 删除token
                Route::delete('authorizations/current', 'AuthorizationsController@destroy')
                    ->name('authorizations.destroy');
            });

        /**
         *
         * 访问频率限制路由
         *
         */
        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
                // 图片列表
                Route::get('files', 'FilesController@index')
                    ->name('files.index');

                // 某个用户的详情
                Route::get('users/{user}', 'UsersController@show')
                    ->name('users.show');

                // 分类列表
                Route::get('categories', 'CategoriesController@index')
                    ->name('categories.index');

                // 分类列表所有文章
                Route::get('cateArticels', 'CategoriesController@cateArticels')
                    ->name('categories.cateArticels');

                // 话题列表，详情
                Route::resource('articles', 'ArticlesController')->only([
                    'index', 'show'
                ]);

                // 搜索文章
                Route::get('search/articles', 'ArticlesController@searchArticle')
                    ->name('arcticle.search');

                // 某个用户发布的话题
                Route::get('users/{user}/articles', 'ArticlesController@userIndex')
                    ->name('users.articles.index');

                // 资源推荐
                Route::get('links', 'LinksController@index')
                    ->name('links.index');

                // 活跃用户
                Route::get('actived/hot', 'ArticlesController@activedIndex')
                    ->name('actived.hot.index');

                /**
                 *
                 * 登录后可以访问的接口
                 *
                 */
                Route::middleware('auth:api')->group(function () {
                    // 当前登录用户信息
                    Route::get('user', 'UsersController@userInfo')
                        ->name('user.show');

                    // 编辑登录用户信息
                    Route::patch('user', 'UsersController@update')
                        ->name('user.update');

                    // 上传图片
                    Route::post('files', 'FilesController@store')
                        ->name('files.store');

                    // 发布话题
                    Route::resource('articles', 'ArticlesController')->only([
                        'store', 'update', 'destroy'
                    ]);
                });
            });
    });
