<?php

namespace Framework\Renderer;

use Framework\DI\Service;

/**
 * Class Renderer for rendering views
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Renderer
{
    /**
     * Renderer class constructor
     *
     * @param string $layout Name of main layout
     */
    public function __construct($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Get path to views
     *
     * @return string Path to views
     */
    private function getViewsPath($notController = false)
    {
        if (!$notController) {
            $controllerShort = Service::get('router')->getControllerShort();
            return dirname($this->layout) . '/' . $controllerShort . '/';
        } else {
            return dirname($this->layout) . '/';
        }
    }

    /**
     * Render view
     *
     * @param string $view View name
     * @param array $params Variables in view file
     * @return string html code
     */
    public function render($view, $params = array(), $notController = false)
    {
        ob_start();

        $getRoute = function($routeName) {
            return Service::get('router')->generateRoute($routeName);
        };

        $include = function($controller, $action, $parameters) {
            Service::get('app')->callController($controller, $action, $parameters);
        };

        $generateToken = function() {
            return '';
        };

        extract($params, EXTR_OVERWRITE);
        require($this->getViewsPath($notController) . $view . '.php');
        $content = ob_get_contents();
        ob_clean();
        require($this->layout);
        $content = ob_get_clean();
        return $content;
    }
}