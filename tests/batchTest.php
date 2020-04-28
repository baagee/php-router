<?php
/**
 * Desc:
 * User: baagee
 * Date: 2020/4/28
 * Time: 下午3:42
 */
include __DIR__ . '/../vendor/autoload.php';

$list = [
    [
        'route' => '/articles',
        'callback' => function ($params = []) {
            echo 'article list' . PHP_EOL;
        },
        'method' => 'get',
        'check' => [
            '/articles', 'get'
        ]
    ],
    [
        'route' => '/article/{article_id}',
        'callback' => function ($params = []) {
            echo 'article info' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'get',
        'check' => [
            '/article/123', 'get'
        ]
    ],
    [
        'route' => '/article/{article_id}',
        'callback' => function ($params = []) {
            echo 'add article' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'post',
        'check' => [
            '/article/456', 'post'
        ]
    ],
    [
        'route' => '/article/{article_id}',
        'callback' => function ($params = []) {
            echo 'update article' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'put',
        'check' => [
            '/article/123', 'put'
        ]
    ],
    [
        'route' => '/article/{article_id}',
        'callback' => function ($params = []) {
            echo 'delete article' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'delete',
        'check' => [
            '/article/123', 'delete'
        ]
    ],
    [
        'route' => '/',
        'callback' => function ($params = []) {
            echo 'article list' . PHP_EOL;
        },
        'method' => 'get',
        'check' => [
            '/', 'get'
        ]
    ],

    [
        'route' => '/car/{car_id}',
        'callback' => function ($params = []) {
            echo 'car info' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'get',
        'check' => [
            '/car/123', 'get'
        ]
    ],
    [
        'route' => '/car/{car_id}',
        'callback' => function ($params = []) {
            echo 'add car' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'post',
        'check' => [
            '/car/456', 'post'
        ]
    ],
    [
        'route' => '/car/{car_id}',
        'callback' => function ($params = []) {
            echo 'update car' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'put',
        'check' => [
            '/car/123', 'put'
        ]
    ],
    [
        'route' => '/car/{car_id}',
        'callback' => function ($params = []) {
            echo 'delete car' . PHP_EOL;
            var_dump($params);
        },
        'method' => 'delete',
        'check' => [
            '/car/123', 'delete'
        ]
    ],
];
