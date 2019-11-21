<?php

include_once __DIR__ . '/../vendor/autoload.php';

// 定义路由规则
$routes = [
    '/get/{id}' => [
        'get',// 允许的请求方法
        function ($params) {// 具体的回调方法
            echo 'get';
            var_dump($params);
            return time();
        },
        //  其他附加信息
        ['other', 'info']
    ],

    '/post[/{name}][/{id}]' => [
        ['post'],
        function ($params) {
            echo 'post';
            var_dump($params);
            return time();
        }, ['other', 'info']
    ],

    '/getpost' => [
        ['post', 'get'],
        function () {
            echo 'post get';
            return time();
        }
    ],
    '/getput'  => [
        'get|put',
        function () {
            return 'put get';
        }
    ],
];
// 批量添加路由
\BaAGee\Router\Router::batchAdd($routes);

\BaAGee\Router\Router::setNotFound(function () {
    echo 'setNotFound';
});

\BaAGee\Router\Router::setMethodNotAllow(function () {
    echo 'setMethodNotAllow';
});
echo \BaAGee\Router\Router::dispatch($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
