<?php

include_once __DIR__ . '/../vendor/autoload.php';

// 定义路由规则
$routes = [
    '/get/{id}' => [
        'methods'  => 'get',// 允许的请求方法
        'callback' => function ($params) {// 具体的回调方法
            echo 'get';
            var_dump($params);
        },
        //  其他附加信息
        'other'    => ['other', 'info']
    ],

    '/post[/{name}][/{id}]' => [
        'methods'  => ['post'],
        'callback' => function ($params) {
            echo 'post';
            var_dump($params);
        },
        'other'    => ['other', 'info']
    ],

    '/getpost' => [
        'methods'  => ['post', 'get'],
        'callback' => function () {
            echo 'post get';
        }
    ],
    '/getput'  => [
        'methods'  => 'get|put',
        'callback' => function () {
            echo 'put get';
        }
    ],
];
// 批量添加路由
\BaAGee\Router\Router::batchAdd($routes);
\BaAGee\Router\Router::dispatch();