<?php

include_once __DIR__ . '/../vendor/autoload.php';

// 定义路由规则
$routes = [
    '/get/{id}' => [
        'get',// 允许的请求方法
        function ($params) {// 具体的回调方法
            echo 'get';
            var_dump($params);
        },
        //  其他附加信息
        ['other', 'info']
    ],

    '/post[/{name}][/{id}]' => [
        ['post'],
        function ($params) {
            echo 'post';
            var_dump($params);
        }, ['other', 'info']
    ],

    '/getpost' => [
        ['post', 'get'],
        function () {
            echo 'post get';
        }
    ],
    '/getput'  => [
        'get|put',
        function () {
            echo 'put get';
        }
    ],
];
// 批量添加路由
\BaAGee\Router\Router::batchAdd($routes);
\BaAGee\Router\Router::dispatch();