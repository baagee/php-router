<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/3/29
 * Time: 下午5:24
 */
include_once __DIR__ . '/../vendor/autoload.php';

class App
{
    public function test1($a)
    {
        var_dump($a);
        echo __FUNCTION__;
    }

    public function test2($a)
    {
        var_dump($a);
        echo __FUNCTION__;
    }

    public function test3($a)
    {
        var_dump($a);
        echo __FUNCTION__;
    }
}

// 添加路由的方法
function addRouter()
{
    \BaAGee\Router\Router::get('/get/test1', 'App@test1');
    \BaAGee\Router\Router::get('/get/test2', 'App@test2');
    \BaAGee\Router\Router::get('/get/{name}', 'App@test3');
    \BaAGee\Router\Router::get('/[{aaa}]', 'App@test3');
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

echo \BaAGee\Router\Router::dispatch();
