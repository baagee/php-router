<?php

include_once __DIR__ . '/../vendor/autoload.php';

// 自定义自己的调用方式
class MyRouter extends \BaAGee\Router\Base\RouterAbstract
{
    protected static function call($callback, $params, $other)
    {
        var_dump($other);
        // 获取控制器和方法
        list($controller, $action) = explode('->', $callback);
        // todo 判断类，方法是否存在...
        $obj = new $controller();
        // todo 调用 中间件
        var_dump($other['middleware']);
        // 调用Action
        call_user_func_array([$obj, $action], $params);
    }
}

MyRouter::get('/get', 'UserController->action', [
    'middleware'     => [
        'CheckLogin',
        'CheckPrivilege'
    ],
    'otherRouteInfo' => [
        '扒拉扒拉一堆...'
    ]
]);

MyRouter::dispatch();
