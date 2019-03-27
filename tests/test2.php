<?php

include_once __DIR__ . '/../vendor/autoload.php';

// 自定义自己的调用方式
class MyRouter extends \BaAGee\Router\Base\RouterAbstract
{
    protected static function call($callback, $params)
    {
        // 获取控制器和方法
        list($controller, $action) = explode('->', $callback);
        // todo 判断类，方法是否存在...
        $obj = new $controller();
        // 调用
        call_user_func_array([$obj, $action], $params);
    }
}

MyRouter::get('/get', 'UserController->action');

MyRouter::dispatch();
