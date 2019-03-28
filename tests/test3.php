<?php

include_once __DIR__ . '/../vendor/autoload.php';

// 定义路由规则
$routes = [
    '/get' => [
        'methods'  => 'get',// 允许的请求方法
        'callback' => function () {// 具体的回调方法
            echo 'get';
        },
        //  其他附加信息
        'other'    => ['other', 'info']
    ],

    '/post/(\d+)' => [
        'methods'  => ['post'],
        'callback' => function ($id) {
            echo 'post id=' . $id;
        },
        'other'    => ['other', 'info']
    ],

    '/get/post' => [
        'methods'  => ['post', 'get'],
        'callback' => function () {
            echo 'post get';
        }
    ],
];
// 批量添加路由
\BaAGee\Router\Router::batchAddRouter($routes);
\BaAGee\Router\Router::dispatch();