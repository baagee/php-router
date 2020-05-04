<?php
/**
 * Desc:
 * User: baagee
 * Date: 2020/5/4
 * Time: 下午10:12
 */
return [
    [
        'route' => '/articles',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => 'get',
        'check' => ['/articles', 'get', 'ok']
    ],
    [
        'route' => '/articles',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => 'post|PUT',
        'check' => ['/articles', 'post', 'ok']
    ],
    [
        'route' => '/article/list',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => 'get',
        'check' => ['/articles', 'get', 'ok']
    ],
    [
        'route' => '/article/{article_id}',
        'callback' => sprintf("%s@%s", Article::class, 'detail'),
        'method' => 'get',
        'check' => ['/article/123', 'get', 'ok']
    ],
    [
        'route' => '/article',
        'callback' => sprintf("%s@%s", Article::class, 'add'),
        'method' => 'post',
        'check' => ['/article', 'post', 'ok']
    ],
    [
        'route' => '/article',
        'callback' => sprintf("%s@%s", Article::class, 'update'),
        'method' => 'put',
        'check' => ['/article', 'put', 'ok']
    ],
    [
        'route' => '/article/{article_id}',
        'callback' => sprintf("%s@%s", Article::class, 'delete'),
        'method' => 'delete',
        'check' => ['/article/12 3', 'delete', 'ok']
    ],
    [
        'route' => '/article/{article_id}/delete',
        'callback' => sprintf("%s@%s", Article::class, 'delete'),
        'method' => 'delete',
        'check' => [['/article/12 345/delete', 'delete', 'ok'], ['/article/12345/deleted', 'delete', '404']]
    ],
    [
        'route' => '/article/delete/{article_id}',
        'callback' => sprintf("%s@%s", Article::class, 'delete'),
        'method' => 'delete',
        'check' => [['/article/delete/12345', 'delete', 'ok'], ['/article/deleted/12345', 'delete', '404']]
    ],
    [
        'route' => '/',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => 'get',
        'check' => ['/', 'get', 'ok']
    ],
    [
        'route' => '/[{id}]',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => 'get',
        'check' => [['/', 'get', 'ok'], ['/fsdfs', 'get', 'ok']]
    ],
    [
        'route' => '/{id}',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => 'get',
        'check' => [['/', 'get', 'ok'], ['/4444555', 'get', 'ok']]
    ],
    // Car
    [
        'route' => '/car',
        'callback' => sprintf("%s@%s", Car::class, 'list'),
        'method' => 'get',
        'check' => ['/car', 'get', 'ok']
    ],
    [
        'route' => '/car/{car_id}',
        'callback' => sprintf("%s@%s", Car::class, 'detail'),
        'method' => 'get',
        'check' => [['/car/123', 'get', 'ok'], ['/car/asd/asd', 'put', '404']]
    ],
    [
        'route' => '/car[/{car_id}]',
        'callback' => sprintf("%s@%s", Car::class, 'detail'),
        'method' => 'get',
        'check' => [['/car/123', 'get', 'ok'], ['/car', 'get', 'ok']]
    ],
    [
        'route' => '/car_info/{car_id}',
        'callback' => sprintf("%s@%s", Car::class, 'detail'),
        'method' => 'get',
        'check' => [['/car_info/123', 'get', 'ok'], ['/car/asd/asd', 'put', '404']]
    ],
    [
        'route' => '/car',
        'callback' => sprintf("%s@%s", Car::class, 'add'),
        'method' => 'post',
        'check' => ['/car', 'post', 'ok']
    ],
    [
        'route' => '/car',
        'callback' => sprintf("%s@%s", Car::class, 'update'),
        'method' => 'put',
        'check' => [['/car', 'put', 'ok'], ['/update_car', 'put', '404']]
    ],
    [
        'route' => '/car/{car_id}',
        'callback' => sprintf("%s@%s", Car::class, 'delete'),
        'method' => 'delete',
        'check' => [['/car/123', 'delete', 'ok'],['/car/123', 'head', '405']]
    ],
];