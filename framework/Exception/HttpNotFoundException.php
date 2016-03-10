<?php

namespace Framework\Exception;

/**
 * Class HttpNotFoundException
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 * @package Framework\Exception
 */
class HttpNotFoundException extends \Exception
{
    /**
     * HttpNotFoundException constructor
     *
     * @param string $message Exception message
     * @param int $code Exception code
     * @param \Exception $previous Previous exception
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct('HttpNotFound: ' . $message, $code, $previous);
    }
}