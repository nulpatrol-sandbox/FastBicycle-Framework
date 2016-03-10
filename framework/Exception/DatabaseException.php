<?php

namespace Framework\Exception;

/**
 * Class DatabaseException
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 * @package Framework\Exception
 */
class DatabaseException extends \PDOException
{
    /**
     * DatabaseException constructor
     *
     * @param string $message Exception message
     * @param int $code Exception code
     * @param \Exception $previous Previous exception
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct('Database: ' . $message, $code, $previous);
    }
}