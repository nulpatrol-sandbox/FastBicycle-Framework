<?php

namespace Framework\Router;

/**
 * Class Router implements URL-routing
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Router
{
    /**
     * @var array $routes Array of routes
     */
    private $routes;

    /**
     * @var array $matchedRoute Matched route
     */
    private $matchedRoute;

    /**
     * Router class constructor
     *
     * @param array $routes Array of routes
     */
    public function __construct($routes = array())
    {
        $this->routes = $routes;
        $this->matchRoute();
    }

    /**
     * Get path to route
     *
     * @param $routeName Name of route
     * @return string Path to route
     */
    public function generateRoute($routeName)
    {
        return $this->routes[$routeName]['pattern'];
    }

    /**
     * Get controller name
     *
     * @return string Controller name
     */
    public function getController()
    {
        return $this->matchedRoute['controller'];
    }

    /**
     * Get short controller name
     *
     * @return string Short controller name
     */
    public function getControllerShort()
    {
        $controller_name = array_pop(explode('\\', $this->getController()));
        return substr($controller_name, 0, strpos($controller_name, 'Controller'));
    }

    /**
     * Get action name
     *
     * @return string Action name
     */
    public function getAction()
    {
        return $this->matchedRoute['action'];
    }

    /**
     * Get arguments
     *
     * @return array Arguments for action
     */
    public function getArgs()
    {
        return $this->matchedRoute['args'];
    }

    /**
     * Match route
     */
    private function matchRoute()
    {
        $matchedRoute = null;
        foreach ($this->routes as $name => $routeParam) {
            $compiledRoute = $this->compileRoute($routeParam);
            if (preg_match($compiledRoute, $_SERVER['REQUEST_URI'], $matches)) {
                $matchedRoute = $routeParam;
                array_shift($matches);
                $matchedRoute['args'] = $matches;
                $matchedRoute['_name'] = ltrim($name, '/');
            }
        }
        $this->matchedRoute = $matchedRoute;
    }

    /**
     * Make regexp pattern to routes
     *
     * @param array $route Route
     * @return string Regexp pattern
     */
    private function compileRoute($route) {
        $compiledRoute = '#'.$route['pattern'].'#';
        if (isset($route['_requirements'])) {
            foreach ($route['_requirements'] as $reqKey => $reqVal) {
                $compiledRoute = str_replace('{' . $reqKey . '}', '(' . $reqVal . ')', $compiledRoute);
            }
        }
        return $compiledRoute;
    }
}
