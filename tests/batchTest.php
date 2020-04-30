<?php
/*
 * 批量验证测试
 */
include __DIR__ . '/../vendor/autoload.php';

define('TEST_FDSERFWERTER', true);

class Article
{
    public function add($params)
    {
        echo __METHOD__ . ' params:' . json_encode($params, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        return 'ok';
    }

    public function detail($params)
    {
        echo __METHOD__ . ' params:' . json_encode($params, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        return 'ok';
    }

    public function list($params)
    {
        echo __METHOD__ . ' params:' . json_encode($params, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        return 'ok';
    }

    public function update($params)
    {
        echo __METHOD__ . ' params:' . json_encode($params, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        return 'ok';
    }

    public function delete($params)
    {
        echo __METHOD__ . ' params:' . json_encode($params, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        return 'ok';
    }
}

class Car extends Article
{
}

$list = [
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
        'check' => ['/article/123', 'delete', 'ok']
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
        'check' => [['/', 'get', 'ok'],['/fsdfs', 'get', 'ok']]
    ],
    [
        'route' => '/{id}',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => 'get',
        'check' => [['/', 'get', 'ok'],['/4444555', 'get', 'ok']]
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
        'check' => [['/car/123', 'get', 'ok'], ['/car/asd/asd', 'put', '405']]
    ],
    [
        'route' => '/car_info/{car_id}',
        'callback' => sprintf("%s@%s", Car::class, 'detail'),
        'method' => 'get',
        'check' => [['/car_info/123', 'get', 'ok'], ['/car/asd/asd', 'put', '405']]
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
        'check' => ['/car/123', 'delete', 'ok']
    ],
];

// 是否开发模式
$isDebug = false;
// 如果不是开发模式(false)，设置一个路由缓存路径，
//      如果缓存文件存在，会返回true，直接跳过，执行dispatch
//      如果缓存文件不存在，会返回false,然后添加路由，最后执行dispatch，
//      请求结束时将路由信息写入缓存文件，下次执行时文件存在，返回true，
//      跳过添加路由，直接执行dispatch
// 如果是开发模式(true)，每次都走添加路由的方法，然后执行dispatch
if ($isDebug || \BaAGee\Router\Router::setCachePath(__DIR__ . '/cache/batch') === false) {
    echo '没有缓存' . PHP_EOL;
    batchAddRouter($list);
}

function batchAddRouter($list)
{
    foreach ($list as $item) {
        \BaAGee\Router\Router::add($item['method'], $item['route'], $item['callback']);
    }
}


// 开始测试匹配
foreach ($list as $item) {
    if (count($item['check']) == count($item['check'], COUNT_RECURSIVE)) {
        check($item['check'][0], $item['check'][1], $item['callback'], $item['check'][2]);
    } else {
        foreach ($item['check'] as $value) {
            check($value[0], $value[1], $item['callback'], $value[2]);
        }
    }
}
// 匹配并验证
function check($path, $method, $callback, $expect)
{
    echo sprintf("method:%s\t path:%s\t callback:%s" . PHP_EOL,$method, $path,  $callback);
    $ret = \BaAGee\Router\Router::dispatch($path, $method);
    if ($ret === $expect) {
        echo "Check [SUCCESS] 👌 Response " . $ret . PHP_EOL;
    } else {
        echo "Check [FAILED] ⚠️ Response " . $ret . PHP_EOL;
    }
    echo str_repeat('-', 100) . PHP_EOL;
}

echo 'Over!' . PHP_EOL;
