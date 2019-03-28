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
    protected static $routes = [];

    /**
     * @var null 404路由找不到的处理
     */
    protected static $notFound = null;

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
     * 批量添加路由
     * @param array $routes
     * @throws \Exception
     */
    final public static function batchAddRouter(array $routes)
    {
        $checkRoutes = [];
        foreach ($routes as $path => $route) {
            $res                       = self::checkRoute($path, $route['methods'], $route['callback']);
            $checkRoutes[$res['path']] = [
                'methods'  => $res['methods'],
                'callback' => $res['callback'],
                'other'    => (isset($route['other']) && !empty($route['other'])) ? $route['other'] : []
            ];
        }
        static::$routes = $checkRoutes;
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
        $path    = preg_replace('/\/+/', '/', strpos($path, '/') === 0 ? $path : '/' . $path);
        $methods = array_map('strtoupper', is_array($methods) ? $methods : [$methods]);
        $diff    = array_diff($methods, static::ALLOW_METHODS);
        if (!empty($diff)) {
            throw new \Exception(sprintf('[%s]存在不合法的请求方法[%s]', $path, implode(', ', $diff)));
        }
        $cType = gettype($callback);
        if (!in_array($cType, self::ALLOW_CALLBACK_TYPE)) {
            throw new \Exception(sprintf('[%s]路由回调方法不合法，不允许[%s]类型，只允许[%s]类型', $path, $cType, implode(', ', self::ALLOW_CALLBACK_TYPE)));
        }
        return compact('path', 'methods', 'callback');
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
        $res                   = self::checkRoute($path, $method, $callback);
        static::$routes[$path] = [
            'methods'  => $res['methods'],
            'callback' => $res['callback'],
            'other'    => $other
        ];
    }

    /**
     * 设置路由找不到的处理
     * @param $callback
     */
    final public static function setNotFound($callback)
    {
        static::$notFound = $callback;
    }

    /**
     * 路由分配
     */
    final public static function dispatch()
    {
        $requestPath   = $_SERVER['PATH_INFO'];
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        if (in_array($requestPath, array_keys(static::$routes))) {
            $routerDetail = static::$routes[$requestPath];
            if (in_array($requestMethod, $routerDetail['methods'])) {
                static::call($routerDetail['callback'], [], $routerDetail['other']);
            } else {
                // 405
                http_response_code(405);
            }
            return;
        } else {
            // 正则
            foreach (static::$routes as $path => $routerDetail) {
                $res = preg_match('#^' . $path . '$#', $requestPath, $matched);
                if ($res === 0 || $res === false) {
                } else {
                    if (in_array($requestMethod, $routerDetail['methods'])) {
                        array_shift($matched);
                        static::call($routerDetail['callback'], $matched, $routerDetail['other']);
                    } else {
                        // 405
                        http_response_code(405);
                    }
                    return;
                }
            }
        }
        // 404
        if (static::$notFound !== null) {
            static::$routes = [];
            static::get($_SERVER['PATH_INFO'], static::$notFound);
            static::$notFound = null;
            static::dispatch();
        } else {
            http_response_code(404);
        }
        return;
    }

    /**
     * 具体的调用逻辑
     * @param $callback
     * @param $params
     * @param $other
     * @return mixed
     */
    abstract protected static function call($callback, $params, $other);
}
