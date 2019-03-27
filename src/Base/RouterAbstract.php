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
 * @method static get(string $route, callable $callback)
 * @method static post(string $route, Callable $callback)
 * @method static put(string $route, Callable $callback)
 * @method static delete(string $route, Callable $callback)
 * @method static options(string $route, Callable $callback)
 * @method static head(string $route, Callable $callback)
 * @package BaAGee\Router\Base
 */
abstract class RouterAbstract implements RouterInterface
{
    /**
     * @var array http允许的请求方式
     */
    protected const ALLOW_METHODS = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD'];

    /**
     * [
     *      '/path'=>[
     *          'methods'=>['get','post'],
     *          'callback'=>function(){}
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
        static::add($name, $arguments[0], $arguments[1]);
    }

    /**
     * 添加路由
     * @param string|array $method
     * @param string       $path
     * @param              $callback
     * @throws \Exception
     */
    final public static function add($method, string $path, $callback)
    {
        $path    = preg_replace('/\/+/', '/', strpos($path, '/') === 0 ? $path : '/' . $path);
        $methods = array_map('strtoupper', is_array($method) ? $method : [$method]);
        $diff    = array_diff($methods, static::ALLOW_METHODS);
        if (!empty($diff)) {
            throw new \Exception(sprintf('不合法的请求方法[%s]', implode(', ', $diff)));
        }
        static::$routes[$path] = [
            'methods'  => $methods,
            'callback' => $callback
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
            $methodAndCallback = static::$routes[$requestPath];
            if (!in_array($requestMethod, $methodAndCallback['methods'])) {
            } else {
                static::call($methodAndCallback['callback'], []);
                return;
            }
        } else {
            // 正则
            foreach (static::$routes as $path => $methodAndCallback) {
                $res = preg_match('#^' . $path . '$#', $requestPath, $matched);
                if ($res === 0 || $res === false) {
                } else {
                    if (in_array($requestMethod, $methodAndCallback['methods'])) {
                        array_shift($matched);
                        static::call($methodAndCallback['callback'], $matched);
                        return;
                    }
                }
            }
        }
        // 404
        if (static::$notFound !== null) {
            static::$routes = [];
            static::get($_SERVER['REQUEST_URI'], static::$notFound);
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
     * @return mixed
     */
    abstract protected static function call($callback, $params);
}
