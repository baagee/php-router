<?php

include_once __DIR__ . '/../vendor/autoload.php';

// 定义路由规则
$routes = [
    [
        'path' => '/get/{id}',
        'method' => 'get',// 允许的请求方法
        'callback' => function ($params) {// 具体的回调方法
            echo 'get';
            var_dump($params);
            return time();
        },
        //  其他附加信息
        'other' => ['other', 'info']
    ],

    [
        'path' => '/post[/{name}][/{id}]',
        'method' => ['post'],
        'callback' => function ($params) {
            echo 'post';
            var_dump($params);
            return time();
        },
        'other' => ['other', 'info']
    ],

    [
        'path' => '/getpost',
        'method' => ['post', 'get'],
        'callback' => function () {
            echo 'post get';
            return time();
        }
    ],
    [
        'path' => '/getput',
        'method' => 'get|put',
        'callback' => function () {
            return 'put get';
        }
    ],
];
$a = microtime(true);
// 批量添加路由
\BaAGee\Router\Router::batchAdd($routes);

\BaAGee\Router\Router::setNotFound(function () {
    echo 'setNotFound';
});

\BaAGee\Router\Router::setMethodNotAllow(function () {
    echo 'setMethodNotAllow';
});
// echo \BaAGee\Router\Router::dispatch($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
for ($i = 0; $i <= 1000; $i++) {
    echo \BaAGee\Router\Router::dispatch('/get/235', 'GET');
}
$b = microtime(true);
var_dump(($b - $a));
