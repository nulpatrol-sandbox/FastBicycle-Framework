<?php

namespace Framework\Response;

/**
 * Class ResponseRedirect for creating response with redirect
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class ResponseRedirect extends Response
{
    /**
     * ResponseRedirect class constructor
     *
     * @param string $url URL to redirecting
     * @param string $content Content
     */
    public function __construct($url, $content = '')
    {
        parent::__construct($content, 302);
        $this->setHeader('Location', $url);
    }
}