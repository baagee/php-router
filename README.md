# php-router 简单路由
php router library

### 安装
`composer require baagee/php-router`

### 使用

支持常见的6中请求方法

1. GET
2. POST
3. PUT
4. DELETE
5. OPTIONS
6. HEAD

示例代码：
```php
include_once __DIR__ . '/../vendor/autoload.php';

// get请求 可以使用匿名函数
\BaAGee\Router\Router::get('/get', function () {
    echo 'get';
});
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
```

### 自定义调用方式
```php
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
```
