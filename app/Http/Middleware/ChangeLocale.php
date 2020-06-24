<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class ChangeLocale
{
    /**
     * ---------------------------------------------------------------
     * 返回数据结构
     * ---------------------------------------------------------------
     */
    public function handle($request, Closure $next)
    {
        if($request->header('accept-language')){
            $language = $request->header('accept-language');

            Cache::put('language'.$request->getClientIp(),$language);

            \App::setLocale($language);
        }else{
            \App::setLocale(Cache::get('language'.$request->getClientIp()));
        }

        return $next($request);
    }
}
