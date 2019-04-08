<?php
/**
 * Desc: RouterAbstract
 * User: baagee
 * Date: 2019/3/27
 * Time: 下午7:02
 */

namespace BaAGee\Router\Base;

/**
 * Class RouterAbstract
 * @method static get(string $route, callable $callback, array $other = [])
 * @method static post(string $route, Callable $callback, array $other = [])
 * @method static put(string $route, Callable $callback, array $other = [])
 * @method static delete(string $route, Callable $callback, array $other = [])
 * @method static options(string $route, Callable $callback, array $other = [])
 * @method static head(string $route, Callable $callback, array $other = [])
 * @package BaAGee\Router\Base
 */
abstract class RouterAbstract implements RouterInterface
{
    /**
     * @var array http允许的请求方式
     */
    protected const ALLOW_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];

    /**
     * 路由回调允许的类型
     */
    protected const ALLOW_CALLBACK_TYPE = ['object', 'string', 'array'];

    /**
     * @var string 路由缓存文件
     */
    protected static $cacheFile = '';

    /**
     * 格式
     * [
     *      '/path'=>[
     *          'methods'=>['GET','POST'],
     *          'callback'=>function(){},
     *          'other'=>[]
     *      ]
     * ]
     * @var array 保存的路由规则
     */
    protected static $routes = [
        'static' => [],//静态路由
        'regexp' => []// 正则表达式路由
    ];

    /**
     * @var null 404路由找不到的处理
     */
    protected static $notFound = null;

    /**
     * @var null 405请求方法不允许
     */
    protected static $methodNotAllow = null;

    /**
     * @param $name
     * @param $arguments
     * @throws \Exception
     */
    final public static function __callStatic($name, $arguments)
    {
        static::add($name, $arguments[0], $arguments[1], !empty($arguments[2]) ? $arguments[2] : []);
    }

    /**
     * 设置一个路由缓存路径，返回缓存文件是否存在
     * @param $path
     * @return bool
     * @throws \Exception
     */
    final public static function setCachePath($path)
    {
        $path = realpath($path);
        if (!is_dir($path) || !is_writeable($path)) {
            if (!@mkdir($path, 0755, true)) {
                throw new \Exception('创建文件夹【' . $path . '】失败');
            }
        }
        static::$cacheFile = $path . DIRECTORY_SEPARATOR . 'routes.php';
        if (is_file(static::$cacheFile)) {
            static::$routes = include_once static::$cacheFile;
            return true;
        } else {
            register_shutdown_function(function () {
                if (static::$cacheFile) {
                    $code = '<?php' . PHP_EOL . '// time:' . date('Y-m-d H:i:s') . PHP_EOL .
                        'return ' . var_export(static::$routes, true) . ';';
                    file_put_contents(static::$cacheFile, $code);
                }
            });
            return false;
        }
    }

    /**
     * 批量添加路由
     * @param array $routes
     * @throws \Exception
     */
    final public static function batchAdd(array $routes)
    {
        foreach ($routes as $path => $route) {
            static::add($route[0], $path, $route[1], (isset($route[2]) && !empty($route[2])) ? $route[2] : []);
        }
    }

    /**
     * 检查路由是否合法
     * @param string                $path     请求路径
     * @param string|array          $methods  请求方法
     * @param string|array|\Closure $callback 请求回调
     * @return array
     * @throws \Exception
     */
    private static function checkRoute($path, $methods, $callback)
    {
        $path = preg_replace('/\/+/', '/', strpos($path, '/') === 0 ? $path : '/' . $path);
        $res1 = strpos($path, '[') !== false && strpos($path, ']') !== false;
        $res2 = strpos($path, '{') !== false && strpos($path, '}') !== false;
        if ($res1 || $res2) {
            // 路由规则使用了正则表达式，转化为标准正则
            $path     = preg_replace('`\{(\S+?)\}`', '(?<$1>\S+?)', str_replace(['[', ']', '/'], ['(?:', ')?', '\/'], $path));
            $isStatic = false;
        } else {
            // 没有正则表达式
            $isStatic = true;
        }
        $methods = array_map('strtoupper', is_array($methods) ? $methods : explode('|', $methods));
        $diff    = array_diff($methods, static::ALLOW_METHODS);
        if (!empty($diff)) {
            throw new \Exception(sprintf('[%s]存在不合法的请求方法[%s]', $path, implode(', ', $diff)));
        }
        static::checkCallbackType($callback);
        return compact('path', 'methods', 'callback', 'isStatic');
    }

    /**
     * 添加路由
     * @param string|array $method
     * @param string       $path
     * @param              $callback
     * @param array        $other
     * @throws \Exception
     */
    final public static function add($method, string $path, $callback, $other = [])
    {
        $res = static::checkRoute($path, $method, $callback);
        if ($res['isStatic']) {
            static::$routes['static'][$res['path']] = [
                'methods'  => $res['methods'],
                'callback' => $res['callback'],
                'other'    => $other
            ];
        } else {
            // 正则表达式
            $char = $res['path']{2};
            $dd   = preg_match('/[a-zA-Z]/', $char);
            if ($dd === false || $dd === 0) {
                // 没有匹配到
                $char = '/';
            }
            static::$routes['regexp'][$char][$res['path']] = [
                'methods'  => $res['methods'],
                'callback' => $res['callback'],
                'other'    => $other
            ];
        }
    }

    /**
     * 检查回调是否合法
     * @param $callback
     * @throws \Exception
     */
    final protected static function checkCallbackType($callback)
    {
        $cType = gettype($callback);
        if (!in_array($cType, static::ALLOW_CALLBACK_TYPE)) {
            throw new \Exception(sprintf('路由回调方法不合法，不允许[%s]类型，只允许[%s]类型', $cType, implode(', ', static::ALLOW_CALLBACK_TYPE)));
        }
    }

    /**
     * 设置路由找不到的响应处理
     * @param $callback
     * @throws \Exception
     */
    final public static function setNotFound($callback)
    {
        static::checkCallbackType($callback);
        static::$notFound = $callback;
    }

    /**
     * 设置方法不允许的响应处理
     * @param $callback
     * @throws \Exception
     */
    final public static function setMethodNotAllow($callback)
    {
        static::checkCallbackType($callback);
        static::$methodNotAllow = $callback;
    }

    /**
     * 路由分配
     */
    final public static function dispatch()
    {
        $requestPath   = empty($_SERVER['PATH_INFO']) ? '/' : $_SERVER['PATH_INFO'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (array_key_exists($requestPath, static::$routes['static'])) {
            $routerDetail = static::$routes['static'][$requestPath];
            if (in_array($requestMethod, $routerDetail['methods'])) {
                static::call($routerDetail['callback'], [], $requestMethod, $routerDetail['other']);
            } else {
                static::responseMethodNotAllow();// 405
            }
            return;
        } else {
            // 正则
            $dd = $requestPath{1};
            if (isset(static::$routes['regexp'][$dd])) {
                $foreach = array_merge(static::$routes['regexp'][$dd], static::$routes['regexp']['/'] ?? []);
            } else {
                $foreach = static::$routes['regexp']['/'];
            }
            foreach ($foreach as $path => $routerDetail) {
                $res = preg_match('`^' . $path . '$`', $requestPath, $matched);
                if ($res === 0 || $res === false) {
                } else {
                    if (in_array($requestMethod, $routerDetail['methods'])) {
                        foreach ($matched as $k => $v) {
                            if (!is_string($k)) {
                                unset($matched[$k]);
                            }
                        }
                        static::call($routerDetail['callback'], $matched, $requestMethod, $routerDetail['other']);
                    } else {
                        static::responseMethodNotAllow();// 405
                    }
                    return;
                }
            }
        }
        // 404
        static::responseNotFound();
        return;
    }

    /**
     * 响应404
     */
    final protected static function responseNotFound()
    {
        if (static::$notFound !== null) {
            static::$routes = [];
            static::get($_SERVER['PATH_INFO'], static::$notFound);
            static::$notFound = null;
            static::dispatch();
        } else {
            http_response_code(404);
        }
    }

    /**
     * 响应405
     */
    final protected static function responseMethodNotAllow()
    {
        if (static::$methodNotAllow !== null) {
            static::$routes = [];
            static::get($_SERVER['PATH_INFO'], static::$methodNotAllow);
            static::$methodNotAllow = null;
            static::dispatch();
        } else {
            http_response_code(405);
        }
    }

    /**
     * 具体的调用方法
     * @param string|\Closure $callback 路由回调方法
     * @param array           $params   请求路由中的参数
     * @param string          $method   请求方法
     * @param array           $other    其他路由辅助信息
     */
    abstract protected static function call($callback, $params, $method, $other);
}
