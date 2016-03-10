<?php

namespace Framework\DI;

/**
 * Class Service implements dependency injection
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 * @package Framework\DI
 */
class Service
{
    /**
     * @var array $storage Storage with instances of classes
     */
    private static $storage = array();

    /**
     * Service class constructor
     */
    private final function __construct() {}

    /**
     * Get instance of some element of storage
     *
     * @param string $elemName Element name
     * @return object|array|null
     */
    public static function get($elemName)
    {
        if (array_key_exists($elemName, self::$storage)) {
            return self::$storage[$elemName];
        } else {
            return null;
        }
    }

    /**
     * Set instance of some element of storage
     *
     * @param string $elemName Element name
     * @param object|array $value
     */
    public static function set($elemName, $value)
    {
        self::$storage[$elemName] = $value;
    }
}