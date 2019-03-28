<?php
/**
 * Desc: PHP简单路由
 * User: baagee
 * Date: 2019/3/27
 * Time: 下午7:02
 */

namespace BaAGee\Router;

use BaAGee\Router\Base\RouterAbstract;

/**
 * Class Router
 * @package BaAGee\Router
 */
class Router extends RouterAbstract
{
    /**
     * 调用方法
     * @param string|\Closure $callback 路由回调方法
     * @param array           $params   请求路由中的参数
     * @param string          $method   请求方法
     * @param array           $other    其他路由辅助信息
     * @throws \Exception
     */
    protected static function call($callback, $params, $method, $other = [])
    {
        if ($callback instanceof \Closure) {
            call_user_func($callback, $params);
        } elseif (is_string($callback) || is_array($callback)) {
            if (is_string($callback)) {
                $callback = explode('@', $callback);
            }
            list($controller, $action) = $callback;
            if (class_exists($controller)) {
                $obj = new $controller();
                if (method_exists($obj, $action)) {
                    call_user_func([$obj, $action], $params);
                } else {
                    throw new \Exception(sprintf('[%s]控制器的[%s]方法不存在', $controller, $action));
                }
            } else {
                throw new \Exception(sprintf('[%s]控制器不存在', $controller));
            }
        } else {
            throw new \Exception(sprintf('不合法的callback路由回调'));
        }
    }
}
