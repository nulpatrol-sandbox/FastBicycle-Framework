<?php

namespace Framework\Controller;

use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Response\Response;
use Framework\Request\Request;
use Framework\Response\ResponseRedirect;

/**
 * Class Controller implements basic functionality of controllers
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Controller
{
    /**
     * Render view file
     *
     * @param string $view Name of view
     * @param array $params Variables in view file
     * @return Response Response with rendered view file
     */
    public function render($view, $params = array())
    {
        $renderer = new Renderer(Service::get('config')->main_layout);
        $content = $renderer->render($view, $params);
        return new Response($content);
    }

    /**
     * Get Request instance
     *
     * @return Request Instance of Request class
     */
    public function getRequest()
    {
        return Service::get('request');
    }

    /**
     * Generate route
     *
     * @param string $routeName Name of route
     * @return string Path to route
     */
    public function generateRoute($routeName)
    {
        return Service::get('router')->generateRoute($routeName);
    }

    /**
     * Redirect
     *
     * @param string $url URL to redirecting
     * @param string $message Message
     * @return ResponseRedirect Instance of ResponseRedirect
     */
    public function redirect($url, $message = null)
    {
        return new ResponseRedirect($url, $message);
    }
}