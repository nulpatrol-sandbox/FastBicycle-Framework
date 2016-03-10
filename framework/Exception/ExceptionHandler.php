<?php

namespace Framework\Exception;

use Exception;
use Framework\DI\Service;
use Framework\Renderer\Renderer;
use Framework\Response\Response;

/**
 * Class ExceptionHandler
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 * @package Framework\Exception
 */
class ExceptionHandler
{
    public function handleException(\Exception $e)
    {
        $renderer = new Renderer(Service::get('config')->main_layout);
        $content = $renderer->render('500.html', array(
            'code' => $e->getCode(),
            'message' => htmlentities($e->getMessage())
        ), true);
        $response = new Response($content);
        $response->send();
    }
}