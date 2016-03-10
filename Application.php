<?php

namespace Framework;

use Framework\Config\Config;
use Framework\Request\Request;
use Framework\Router\Router;
use Framework\DI\Service;
use Framework\Exception\ExceptionHandler;

/**
 * Class Application main framework class
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Application
{
    /**
     * Application class constructor
     *
     * @param string $configPath Path to file with config
     */
    public function __construct($configPath = '')
    {
        error_reporting(E_ERROR);
        set_exception_handler(array('Framework\Exception\ExceptionHandler', 'handleException'));
        Service::set('config', Config::instantiate($configPath));
        Service::set('db', new \PDO(
            Service::get('config')->pdo['dns'],
            Service::get('config')->pdo['user'],
            Service::get('config')->pdo['password']
        ));
        Service::set('router', new Router(Service::get('config')->routes));
        Service::set('request', new Request());
        Service::set('app', $this);
    }

    /**
     * Call controller
     *
     * @param string $controller Controller name
     * @param string $action Action name
     * @param array $args Arguments
     */
    public function callController($controller, $action, $args)
    {
        $response = call_user_func_array(array($controller, $action.'Action'), $args);
        $response->send();
    }

    /**
     * Run application
     */
    public function run()
    {
        $this->processRouter();
    }

    /**
     * Get route parameters and run controller and call action
     */
    public function processRouter()
    {
        $controllerName = Service::get('router')->getController();
        $controller = new $controllerName();
        $action     = Service::get('router')->getAction();
        $args       = Service::get('router')->getArgs();
        $this->callController($controller, $action, $args);
    }
}
