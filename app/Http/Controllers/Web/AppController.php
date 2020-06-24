<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppController extends Controller
{

    /**
     * ---------------------------------------------------------------
     * 获取首页 
     *---------------------------------------------------------------
     * @return Resources    返回视图
     */
    public function index()
    {
        return view('web.app.app');
    }

    /**
     * ---------------------------------------------------------------
     * 获取登录页面 
     * ---------------------------------------------------------------
     * @return Resources    返回视图
     */
    public function doLogin()
    {
        return view('web.login.login');
    }
}
