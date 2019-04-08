<?php
/**
 * Desc: 路由接口
 * User: baagee
 * Date: 2019/3/27
 * Time: 下午10:54
 */

namespace BaAGee\Router\Base;

interface RouterInterface
{
    public static function add($methods, string $path, $callback, $other = []);

    public static function dispatch();

    public static function setNotFound($callback);

    public static function setMethodNotAllow($callback);
}
