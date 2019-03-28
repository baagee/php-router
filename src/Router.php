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
     * @param $callback
     * @param $params
     * @param $other
     * @throws \Exception
     */
    protected static function call($callback, $params, $other = [])
    {
        if ($callback instanceof \Closure) {
            call_user_func_array($callback, $params);
        } elseif (is_string($callback) || is_array($callback)) {
            if (is_string($callback)) {
                $callback = explode('@', $callback);
            }
            list($controller, $action) = $callback;
            if (class_exists($controller)) {
                $obj = new $controller();
                if (method_exists($obj, $action)) {
                    call_user_func_array([$obj, $action], $params);
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
