<?php

/**
 * ---------------------------------------------------------------
 * 路由名称转换为 CSS 类名称
 * ---------------------------------------------------------------
 * @return [type] [description]
 */
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

/**
 * ---------------------------------------------------------------
 * 删除
 * ---------------------------------------------------------------
 * @param string $value 传入的值
 *
 * @return string
 */
function make_excerpt($value, $length = 168)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}

/**
 * ---------------------------------------------------------------
 * 后台link
 * ---------------------------------------------------------------
 * @param string $title 标题
 * @param Model  $model 传入的模型参数
 *
 * @return string
 */
function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

/**
 * ---------------------------------------------------------------
 * 生成url链接
 * ---------------------------------------------------------------
 * @param string $title 标题
 * @param Model  $model 输入的模型
 * @param string $prefix 前缀名称
 *
 * @return string
 */
function model_link($title, $model, $prefix = '')
{
    // 获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 使用站点 URL 拼接全量 URL
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 拼接 HTML A 标签，并返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

/**
 * ---------------------------------------------------------------
 * 获取model
 * ---------------------------------------------------------------
 * @param Model $model
 *
 * @return string
 */
function model_plural_name($model)
{
    // 从实体中获取完整类名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
    $snake_case_name = Str::snake($class_name);

    // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
    return Str::plural($snake_case_name);
}

/**
 * ---------------------------------------------------------------
 * 将多个一维数组合拼成二维数组
 * ---------------------------------------------------------------
 * @param  Array $keys 定义新二维数组的键值，每个对应一个一维数组
 * @param  Array $args 多个一维数组集合
 * @return Array
 */
function array_merge_more($keys, ...$arrs)
{
    // 检查参数是否正确
    if (!$keys || !is_array($keys) || !$arrs || !is_array($arrs) || count($keys) != count($arrs)) {
        return array();
    }
    // 一维数组中最大长度
    $max_len = 0;
    // 整理数据，把所有一维数组转重新索引
    for ($i = 0, $len = count($arrs); $i < $len; $i++) {
        $arrs[$i] = array_values($arrs[$i]);
        if (count($arrs[$i]) > $max_len) {
            $max_len = count($arrs[$i]);
        }
    }
    // 合拼数据
    $result = array();
    for ($i = 0; $i < $max_len; $i++) {
        $tmp = array();
        foreach ($keys as $k => $v) {
            if (isset($arrs[$k][$i])) {
                $tmp[$v] = $arrs[$k][$i];
            }
        }
        $result[] = $tmp;
    }
    return $result;
}

/**
 * ---------------------------------------------------------------
 * 数组去重
 * ---------------------------------------------------------------
 * @param   $str       数组
 * @param   $charset   编码
 * @return  array
 */
function mbStringToArray($str, $charset)
{
    $strlen = mb_strlen($str);
    $array = [];
    while ($strlen) {
        $array[] = mb_substr($str, 0, 1, $charset);
        $str = mb_substr($str, 1, $strlen, $charset);
        $strlen = mb_strlen($str);
    }
    return $array;
}

/**
 * ---------------------------------------------------------------
 * 字符串去重
 * ---------------------------------------------------------------
 * @param   $str       字符串
 * @return  string
 */
function uniqidStr($str)
{
    $arr = [];
    for ($i = 0; $i < strlen($str); $i++) {
        if (!in_array($str[$i], $arr)) {
            $arr[] = $str[$i];
        }
    }
    return $arr;
}

/**
 * ---------------------------------------------------------------
 * 去掉空白字符
 * ---------------------------------------------------------------
 * @param string $str 接收的字符串
 *
 * @return string
 */
function trimStrValue($str)
{
    $search = array(" ", "　", "\n", "\r", "\t");
    $replace = array("", "", "", "", "");
    return str_replace($search, $replace, $str);
}

/**
 * ---------------------------------------------------------------
 * 去掉特殊字符
 * ---------------------------------------------------------------
 * @param string $str 接收的字符串
 *
 * @return string
 */
function replaceSpecialChar($str)
{
    $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\！|\/|\;|\'|\`|\-|\=|\\\|\|/";
    return preg_replace($regex, "", $str);
}

/**
 * ---------------------------------------------------------------
 * 手机号 && 邮箱 打***
 * ---------------------------------------------------------------
 * @param string $str 接收的字符串
 *
 * @return string
 */
function substrReplace($str)
{
    if (strpos($str, '@')) {
        $email_array = explode("@", $str);
        $prevfix = (strlen($email_array[0]) < 4) ? "" : substr($str, 0, 3); //邮箱前缀
        $count = 0;
        $str = preg_replace('/([\d\w+_-]{0,100})@/', '***@', $str, -1, $count);
        $result = $prevfix . $str;
    } else {
        $pattern = '/((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}/i';
        if (preg_match($pattern, $str)) {
            $result = preg_replace($pattern, '$1****$2', $str);
        } else {
            $result = substr($str, 0, 3) . "***" . substr($str, -1);
        }
    }
    return $result;
}
