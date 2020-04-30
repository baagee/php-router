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
 * @method static patch(string $route, Callable $callback, array $other = [])
 * @method static trace(string $route, Callable $callback, array $other = [])
 * @package BaAGee\Router\Base
 */
abstract class RouterAbstract implements RouterInterface
{
    /**
     * @var array http允许的请求方式
     */
    protected const ALLOW_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD', 'PATCH', 'TRACE'];

    /**
     * 路由回调允许的类型
     */
    protected const ALLOW_CALLBACK_TYPE = ['object', 'string', 'array'];

    /**
     * @var string 路由缓存文件
     */
    protected static $cacheFile = '';

    /**
     * @var array 保存的路由规则
     */
    protected static $routes = [
        'static' => [],// 静态路由
        'regexp' => [],// 正则表达式路由
        'entry' => [],// 回调
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
     * @var bool 路由回调是否含有匿名函数
     */
    protected static $hasClosure = false;

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
        if ($path[0] !== DIRECTORY_SEPARATOR) {
            $path = realpath($path);
        }
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
                try {
                    if (!empty(static::$cacheFile) && static::$hasClosure == false) {
                        //只缓存路由中没有匿名函数的，因为匿名函数不太好序列化储存
                        $code = '<?php' . PHP_EOL . '// time:' . date('Y-m-d H:i:s') . PHP_EOL .
                            'return ' . var_export(static::$routes, true) . ';';
                        file_put_contents(static::$cacheFile, $code);
                    } else {
                        if (is_file(static::$cacheFile)) {
                            unlink(static::$cacheFile);
                        }
                    }
                } catch (\Throwable $e) {
                    // 为了不影响后面的register_shutdown_function 要捕获所有的异常
                }
            });
            return false;
        }
    }

    /**
     * 批量添加路由
     * @param array $routes ['method'=>'','path'=>'','callback'=>'','other'=>[]]
     * @throws \Exception
     */
    final public static function batchAdd(array $routes)
    {
        foreach ($routes as $route) {
            static::add($route['method'] ?? '', $route['path'] ?? '', $route['callback'] ?? '', $route['other'] ?? []);
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
    final private static function checkRoute($path, $methods, $callback)
    {
        if (empty($path) || empty($methods) || empty($callback)) {
            throw new \Exception("添加路由失败，参数为空");
        }
        $path = preg_replace('/\/+/', '/', strpos($path, '/') === 0 ? $path : '/' . $path);
        $res1 = strpos($path, '[') !== false && strpos($path, ']') !== false;
        $res2 = strpos($path, '{') !== false && strpos($path, '}') !== false;
        if ($res1 || $res2) {
            // 路由规则使用了正则表达式，转化为标准正则
            $path = preg_replace('`\{(\S+?)\}`', '(?<$1>\S+?)', str_replace(['[', ']', '/'], ['(?:', ')?', '\/'], $path));
            $isStatic = false;
        } else {
            // 没有正则表达式
            $isStatic = true;
        }
        $methods = array_map('strtoupper', is_array($methods) ? $methods : explode('|', $methods));
        $diff = array_diff($methods, static::ALLOW_METHODS);
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
        $path = trim($path);
        $res = static::checkRoute($path, $method, $callback);
        if ($res['callback'] instanceof \Closure) {
            static::$hasClosure = true;
            $entryId = md5(microtime(true) * 10000 + mt_rand(1000, 9999));
        } else {
            $entryId = md5(serialize([$res['callback'], $other]));
        }
        if ($res['isStatic']) {
            foreach ($res['methods'] as $method) {
                if (isset(static::$routes['static'][$method][$res['path']]) && static::$routes['static'][$method][$res['path']] !== $entryId) {
                    throw new \Exception(sprintf("路由规则[%s]已存在但是对应回调不一致", $path));
                }
                static::$routes['static'][$method][$res['path']] = $entryId;
            }
        } else {
            // 正则表达式
            $char = static::getTopKey($path);
            foreach ($res['methods'] as $method) {
                if (isset(static::$routes['regexp'][$method][$char][$res['path']]) && static::$routes['regexp'][$method][$char][$res['path']] !== $entryId) {
                    throw new \Exception(sprintf("路由规则[%s]已存在但是对应回调不一致", $path));
                }
                static::$routes['regexp'][$method][$char][$res['path']] = $entryId;
            }
        }
        if (isset(static::$routes['entry'][$entryId][0]) && is_array(static::$routes['entry'][$entryId][0])) {
            $res['methods'] = array_merge($res['methods'], static::$routes['entry'][$entryId][0]);
        }
        static::$routes['entry'][$entryId] = [array_values(array_unique($res['methods'])), $res['callback'], $other];
    }

    final protected static function getTopKey($route)
    {
        $route = str_replace(['[', ']', '{', '}'], '`', $route);
        $top = trim(substr($route, 0, intval(stripos($route, '`'))), '/');
        return empty($top) ? '/' : $top;
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
            throw new \Exception(sprintf('路由回调方法不合法，不允许[%s]类型，只允许[%s]类型', $cType,
                implode(', ', static::ALLOW_CALLBACK_TYPE)));
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
     * 路由匹配执行
     * @param string $pathInfo
     * @param string $requestMethod
     * @return string
     */
    final public static function dispatch(string $pathInfo, string $requestMethod)
    {
        $requestMethod = strtoupper($requestMethod);
        $requestPath = empty($pathInfo) ? '/' : $pathInfo;
        if (isset(static::$routes['static'][$requestMethod][$requestPath])) {
            $routerDetail = static::$routes['entry'][static::$routes['static'][$requestMethod][$requestPath]] ?? [];
            if (in_array($requestMethod, $routerDetail[0])) {
                $response = static::call($routerDetail[1], [], $requestMethod, $routerDetail[2]);
            } else {
                $response = static::responseMethodNotAllow($pathInfo, $requestMethod);// 405
            }
            return $response;
        } else {
            // 正则
            $dd = array_values(explode('/', trim($requestPath, '/')))[0];
            if (isset(static::$routes['regexp'][$requestMethod][$dd])) {
                $foreach = array_merge(static::$routes['regexp'][$requestMethod][$dd],
                    static::$routes['regexp'][$requestMethod]['/'] ?? []);
            } else {
                $foreach = static::$routes['regexp'][$requestMethod]['/'] ?? [];
            }
            if (!empty($foreach)) {
                foreach ($foreach as $path => $entryId) {
                    if (($matched = static::isMatched($path, $requestPath)) !== false) {
                        $routerDetail = static::$routes['entry'][$entryId] ?? [];
                        if (in_array($requestMethod, $routerDetail[0])) {
                            foreach ($matched as $k => $v) {
                                if (!is_string($k)) {
                                    unset($matched[$k]);
                                }
                            }
                            $response = static::call($routerDetail[1], $matched, $requestMethod, $routerDetail[2]);
                        } else {
                            $response = static::responseMethodNotAllow($pathInfo, $requestMethod);// 405
                        }
                        return $response;
                    }
                }
            }
        }
        if (!isset($dd)) {
            $dd = array_values(explode('/', trim($requestPath, '/')))[0];
        }
        // response method not allow
        foreach (static::$routes['regexp'] as $method => $routes) {
            if (isset($routes[$dd]) && !empty($routes[$dd])) {
                foreach ($routes[$dd] as $route => $entryId) {
                    if (static::isMatched($route, $requestPath) !== false) {
                        if ($method != $requestMethod) {
                            return static::responseMethodNotAllow($pathInfo, $requestMethod);
                        }
                    }
                }
            }
        }
        // response 404
        return static::responseNotFound($pathInfo, $requestMethod);
    }

    /**
     * 是否匹配
     * @param string $route       路由规则
     * @param string $requestPath 请求路径
     * @return bool|array 失败返回false 成功返回匹配到的信息
     */
    final protected static function isMatched($route, $requestPath)
    {
        $res = preg_match('`^' . $route . '$`', $requestPath, $matched);
        if ($res === 0 || $res === false) {
            return false;
        } else {
            return $matched;
        }
    }

    /**
     * 响应404
     * @param $pathInfo
     * @param $requestMethod
     * @return string
     */
    final protected static function responseNotFound($pathInfo, $requestMethod)
    {
        if (static::$notFound !== null) {
            static::$routes = [];
            static::get($pathInfo, static::$notFound);
            static::$notFound = null;
            return static::dispatch($pathInfo, $requestMethod);
        } else {
            $ret = '';
            if (defined('TEST_FDSERFWERTER') && TEST_FDSERFWERTER === true) {
                $ret = '404';
            } else {
                http_response_code(404);
            }
            return $ret;
        }
    }

    /**
     * 响应405
     * @param $pathInfo
     * @param $requestMethod
     * @return string
     */
    final protected static function responseMethodNotAllow($pathInfo, $requestMethod)
    {
        if (static::$methodNotAllow !== null) {
            static::$routes = [];
            static::get($pathInfo, static::$methodNotAllow);
            static::$methodNotAllow = null;
            return static::dispatch($pathInfo, $requestMethod);
        } else {
            $ret = '';
            if (defined('TEST_FDSERFWERTER') && TEST_FDSERFWERTER === true) {
                $ret = '405';
            } else {
                http_response_code(405);
            }
            return $ret;
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
