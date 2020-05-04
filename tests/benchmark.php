<?php
/*
 * 批量验证测试
 */
include __DIR__ . '/../vendor/autoload.php';

define('TEST_FDSERFWERTER', true);

class Article
{
    public function list($params)
    {
        echo __METHOD__ . ' params:' . json_encode($params, JSON_UNESCAPED_UNICODE) . PHP_EOL;
        return 'ok';
    }
}

function randomStr($i = 5)
{
    $a = 'qwertyuioplkjhgfdsazxcvbnm0987654321';
    $ret = '';
    $c = strlen($a) - 1;
    for ($j = 0; $j <= $i; $j++) {
        $ret .= $a[mt_rand(0, $c)];
    }
    return $ret;
}

$list = [];
$ms = [
    'get', 'post', 'put', 'delete'
];
for ($i = 0; $i <= 1000; $i++) {
    $u = randomStr(mt_rand(5, 15));
    $m = $ms[mt_rand(0, 3)];
    $list[] = [
        'route' => '/' . $u . '/{aaa}/{bbb}/{ccc}/{ddd}/{eee}/{fff}/{ggg}/{yyy}/{uuu}/sadgas',
        'callback' => sprintf("%s@%s", Article::class, 'list'),
        'method' => $m,
        'check' => [sprintf('/%s/%s/%s/%s/%s/%s/%s/%s/%s/%s/sadgas', $u, uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true))),
            uniqid(strval(microtime(true)))
                    ), $m, 'ok']
    ];
}
if (is_file(__DIR__ . '/cache/ben/routes.php')) {
    unlink(__DIR__ . '/cache/ben/routes.php');
}
if (\BaAGee\Router\Router::setCachePath(__DIR__ . '/cache/ben') === false) {
    echo '没有缓存' . PHP_EOL;
    batchAddRouter($list);
}

function batchAddRouter($list)
{
    foreach ($list as $item) {
        \BaAGee\Router\Router::add($item['method'], $item['route'], $item['callback']);
    }
}

$s = microtime(true);
// 开始测试匹配
$i = 0;
foreach ($list as $item) {
    if (count($item['check']) == count($item['check'], COUNT_RECURSIVE)) {
        $i++;
        check($item['check'][0], $item['check'][1], $item['callback'], $item['check'][2]);
    } else {
        foreach ($item['check'] as $value) {
            $i++;
            check($value[0], $value[1], $item['callback'], $value[2]);
        }
    }
}
$e = microtime(true);
echo (($e - $s) * 1000 / $i) . 'ms' . PHP_EOL;
// 匹配并验证
function check($path, $method, $callback, $expect)
{
    $resp = \BaAGee\Router\Router::dispatch($path, $method);
}
// 10000 1.5 0.23 0.067 ms
// 4000 0.39 0.097 0.062 ms
// 3000 0.29 0.081 0.056 ms
// 2000 0.21 0.071 0.056 ms
// 1000 0.133 0.093 0.055 ms
// 100 0.060 0.055 0.055 ms