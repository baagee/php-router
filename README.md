# php-router 简单路由
php router library

### 安装
`composer require baagee/php-router`

### 使用

支持8种请求方法

1. GET
2. POST
3. PUT
4. DELETE
5. OPTIONS
6. HEAD
7. PATCH
8. TRACE

示例代码：
```php
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
// {id}是提取参数，保存到$params['id']里面
\BaAGee\Router\Router::get('/user/{id}', 'User@info');
// 或者使用数组指定具体的处理方法：[控制器，方法]
\BaAGee\Router\Router::get('/account/{id}', ['Account', 'info']);
// 可以使用正则表达式定义路由匹配规则
// []包起来的说明这个值可选
\BaAGee\Router\Router::get('/abc/{id}[/{name}]', function ($params) {
    var_dump($params);
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

\BaAGee\Router\Router::setMethodNotAllow(function () {
    http_response_code(405);
    echo '405啊';
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
echo \BaAGee\Router\Router::dispatch($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
```
### 批量添加路由
```php
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
// 批量添加路由
\BaAGee\Router\Router::batchAdd($routes);
echo \BaAGee\Router\Router::dispatch($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
```

### 自定义调用方式
```php
include_once __DIR__ . '/../vendor/autoload.php';
// 自定义自己的调用方式
class MyRouter extends \BaAGee\Router\Base\RouterAbstract
{
    /**
     * 具体的调用方法
     * @param string|\Closure $callback 路由回调方法
     * @param array           $params   请求路由中的参数
     * @param string          $method   请求方法
     * @param array           $other    其他路由辅助信息
     * @throws \Exception
     */
    protected static function call($callback, $params, $method, $other)
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

MyRouter::get('/get[/{id}]', 'UserController->action', [
    'middleware'     => [
        'CheckLogin',
        'CheckPrivilege'
    ],
    'otherRouteInfo' => [
        '扒拉扒拉一堆...'
    ]
]);

echo MyRouter::dispatch($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
```

### 路由缓存

当路由比较多时，每次请求时，add路由会稍微耗时，可以使用缓存来跳过每次请求的路由初始化

```php
include_once __DIR__ . '/../vendor/autoload.php';

class App
{
    public function test1($a)
    {
        echo __FUNCTION__;
    }

    public function test2($a)
    {
        echo __FUNCTION__;
    }
}

// 添加路由的方法
function addRouter()
{
    \BaAGee\Router\Router::get('/get/test1', 'App@test1');
    \BaAGee\Router\Router::get('/get/test2', 'App@test2');
    \BaAGee\Router\Router::get('/get/test3', 'App@test2');
}

// 是否开发模式
$isDebug = false;
// 如果不是开发模式(false)，设置一个路由缓存路径，
//      如果缓存文件存在，会返回true，直接跳过，执行dispatch
//      如果缓存文件不存在，会返回false,然后添加路由，最后执行dispatch，
//      请求结束时将路由信息写入缓存文件，下次执行时文件存在，返回true，
//      跳过添加路由，直接执行dispatch
// 如果是开发模式(true)，每次都走添加路由的方法，然后执行dispatch
if ($isDebug || \BaAGee\Router\Router::setCachePath(__DIR__ . '/cache') === false) {
    echo '没有缓存';
    addRouter();
}

echo \BaAGee\Router\Router::dispatch($_SERVER['PATH_INFO'], $_SERVER['REQUEST_METHOD']);
```

### 注意

`\BaAGee\Router\Router`类虽然定义路由时可以传第三个参数，但是在`call`方法中并没有使用`other`参数，这个属于个性化的参数，路由框架并不会帮你做，要想利用`other`参数，请参考自定义调用方式重新实现`call`方法，详情见`tests/test2.php`文件
