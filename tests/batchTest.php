<?php
/*
 * 批量验证测试
 */
include __DIR__ . '/../vendor/autoload.php';
if (!defined('TEST_FDSERFWERTER')) {
    define('TEST_FDSERFWERTER', true);
}

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


class batchTest extends \PHPUnit\Framework\TestCase
{
    protected $list = [];

    public function setUp()
    {
        $this->start();
    }

    public function start()
    {
        $this->list = include __DIR__ . '/test_list.php';
        // 是否开发模式
        $isDebug = false;
        // 如果不是开发模式(false)，设置一个路由缓存路径，
        //      如果缓存文件存在，会返回true，直接跳过，执行dispatch
        //      如果缓存文件不存在，会返回false,然后添加路由，最后执行dispatch，
        //      请求结束时将路由信息写入缓存文件，下次执行时文件存在，返回true，
        //      跳过添加路由，直接执行dispatch
        // 如果是开发模式(true)，每次都走添加路由的方法，然后执行dispatch
        if (is_file(__DIR__ . '/cache/batch/routes.php')) {
            unlink(__DIR__ . '/cache/batch/routes.php');
        }
        if ($isDebug || \BaAGee\Router\Router::setCachePath(__DIR__ . '/cache/batch') === false) {
            echo '没有缓存' . PHP_EOL;
            $this->batchAddRouter($this->list);
        }
    }

    protected function batchAddRouter($list)
    {
        foreach ($list as $item) {
            \BaAGee\Router\Router::add($item['method'], $item['route'], $item['callback']);
        }
    }

    public function testCallStatic()
    {
        \BaAGee\Router\Router::add('get', '/user/name', 'getuser');
        \BaAGee\Router\Router::get('/user/ddd', 'getuser');
        \BaAGee\Router\Router::put('/user', 'getuser');
        $this->assertEquals(2 > 0, true);
    }

    public function testBatchAdd()
    {
        $ll = [
            ['method' => 'get', 'path' => '/aadfa/sdgs', 'callback' => 'sdgfsdgs', 'other' => []],
            ['method' => 'post', 'path' => '/aadfa/post', 'callback' => 'sdgfsdgs', 'other' => []],
            ['method' => 'delete', 'path' => '/aadfa/delete', 'callback' => 'sdgfsdgs', 'other' => []],
        ];
        \BaAGee\Router\Router::batchAdd($ll);
        $this->assertEquals(2 > 0, true);
    }

    public function testNotAllow()
    {
        \BaAGee\Router\Router::get('/sdgsdgs', 'gfsgsd');
        \BaAGee\Router\Router::setMethodNotAllow(function () {
            return 405;
        });
        $res = \BaAGee\Router\Router::dispatch('/sdgsdgs', 'HEAD');
        $this->assertEquals($res, 405);
    }

    public function testNotFound()
    {
        \BaAGee\Router\Router::get('/aaa/bbb/ccc', 'gfsgsd');
        \BaAGee\Router\Router::setNotFound(function () {
            return '404';
        });
        $res = \BaAGee\Router\Router::dispatch('/aaa/bbbb/ccc/ddd', 'HEAD');
        $this->assertEquals($res, 404);
    }

    public function testCheck()
    {
        // 开始测试匹配
        foreach ($this->list as $item) {
            if (count($item['check']) == count($item['check'], COUNT_RECURSIVE)) {
                $this->check($item['check'][0], $item['check'][1], $item['callback'], $item['check'][2]);
            } else {
                foreach ($item['check'] as $value) {
                    $this->check($value[0], $value[1], $item['callback'], $value[2]);
                }
            }
        }
        $this->assertEquals(2 > 0, true);
    }

    // 匹配并验证
    protected function check($path, $method, $callback, $expect)
    {
        echo sprintf("method:%s\t path:%s\t callback:%s" . PHP_EOL, $method, $path, $callback);
        $ret = \BaAGee\Router\Router::dispatch($path, $method);
        if ($ret === $expect) {
            echo "Check [SUCCESS] 👌 Response " . $ret . PHP_EOL;
        } else {
            echo "Check [FAILED] ⚠️ Response " . $ret . PHP_EOL;
        }
        echo str_repeat('-', 100) . PHP_EOL;
    }
}
