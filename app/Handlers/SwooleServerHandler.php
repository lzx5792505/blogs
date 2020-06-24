<?php

namespace App\Handles;

class SwooleServerHandler
{
    //header Server 全局变量的映射关系
    private static $headerServerMapping = [
        'x-real-ip'       => 'REMOTE_ADDR',
        'x-real-port'     => 'REMOTE_PORT',
        'server-protocol' => 'SERVER_PROTOCOL',
        'server-name'     => 'SERVER_NAME',
        'server-addr'     => 'SERVER_ADDR',
        'server-port'     => 'SERVER_PORT',
        'scheme'          => 'REQUEST_SCHEME',
    ];

    public function onWorkerStart($serv, $worker_id)
    {
        require __DIR__ . '/../../vendor/autoload.php';
        require_once __DIR__ . '/../../bootstrap/app.php';
    }

    public function onRequest($request, $response)
    {
        //server信息
        $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }

        //header头信息
        if (isset($request->header)) {
            foreach ($request->header as $key => $value) {
                if (isset(self::$headerServerMapping[$key])) {
                    $_SERVER[self::$headerServerMapping[$key]] = $value;
                } else {
                    $key = str_replace('-', '_', $key);
                    $_SERVER[strtoupper('http_' . $key)] = $value;
                }
            }
        }
        //是否开启https
        if (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] === 'https') {
            $_SERVER['HTTPS'] = 'on';
        }
        //request uri
        if (
            strpos($_SERVER['REQUEST_URI'], '?') === false && isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0
        ) {
            $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
        }

        //全局的
        if (!isset($_SERVER['argv'])) {
            $_SERVER['argv'] = isset($GLOBALS['argv']) ? $GLOBALS['argv'] : [];
            $_SERVER['argc'] = isset($GLOBALS['argc']) ? $GLOBALS['argc'] : 0;
        }

        //get信息
        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[$k] = $v;
            }
        }

        //post信息
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[$k] = $v;
            }
        }

        //文件请求
        $_FILES = [];
        if (isset($request->files)) {
            foreach ($request->files as $k => $v) {
                $_FILES[$k] = $v;
            }
        }
        //cookie
        $_COOKIE = [];
        if (isset($request->cookie)) {
            foreach ($request->cookie as $k => $v) {
                $_COOKIE[$k] = $v;
            }
        }

        ob_start(); //启用缓存区
        \Illuminate\Http\Request::enableHttpMethodParameterOverride();
        $kernel = app()->make(\Illuminate\Contracts\Http\Kernel::class);
        $laravelResponse = $kernel->handle(
            //Create an Illuminate request from a Symfony instance.
            $request =  \Illuminate\Http\Request::createFromBase(new \Symfony\Component\HttpFoundation\Request($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER, $request->rawContent()))
        );

        $laravelResponse->send();
        $kernel->terminate($request, $laravelResponse);
        $res = ob_get_contents(); //获取缓存区的内容
        ob_end_clean(); //清除缓存区
        //输出缓存区域的内容
        $response->end($res);
    }
}
