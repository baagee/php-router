<?php

include_once __DIR__ . '/../vendor/autoload.php';

// get请求 可以使用匿名函数
\BaAGee\Router\Router::get('/get', function () {
    echo 'get';
}, [
    // 第三个参数可以传其他的一些信息，比如可以传中间件
    'middleware' => [
        'CheckLogin', 'CheckPrivilege', 'GetPhpInputData'
    ]
]);
// 可以使用@符号把控制器把action分离，注意控制器类名为完全限定类名
\BaAGee\Router\Router::get('/user/(\d+)', 'User@info');
// 或者使用数组指定具体的处理方法：[控制器，方法]
\BaAGee\Router\Router::get('/account/(\d+)', ['Account', 'info']);
// 可以使用正则表达式定义路由匹配规则
\BaAGee\Router\Router::get('/abc/(\w+)/(.*?)', function ($a, $b) {
    var_dump($a, $b);
});
// 只允许post请求
\BaAGee\Router\Router::post('/post', function () {
    echo 'post';
});
// put请求
\BaAGee\Router\Router::put('/put', function () {
    echo 'put';
});
// delete请求
\BaAGee\Router\Router::delete('/delete', function () {
    echo 'delete';
});
// head请求
\BaAGee\Router\Router::head('/head', function () {
    echo 'head';
});
// options请求
\BaAGee\Router\Router::options('/options', function () {
    echo 'options';
});
// 设置路由匹配失败的处理
\BaAGee\Router\Router::setNotFound(function () {
    http_response_code(404);
    echo '404啊';
});
// 添加路由 post请求
\BaAGee\Router\Router::add('post', '/post2', function () {
    echo 'post2';
});
// 添加路由，允许的方法，可以支持多种请求方法
\BaAGee\Router\Router::add(['get', 'post'], '/get/post', function () {
    echo 'get/post';
});
// 开始匹配路由并调用对应的回调方法
\BaAGee\Router\Router::dispatch();