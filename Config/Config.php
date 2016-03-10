<?php

namespace Framework\Config;

/**
 * Class Config for reading config
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Config
{
    /**
     * @var array Array with config
     */
    private $config = array();

    /**
     * @var Config Instance of Config class
     */
    private static $instance;

    /**
     * Config class constructor
     *
     * @param string $configPath Path to config file
     */
    private function __construct($configPath)
    {
        $this->config = require_once($configPath);
    }

    /**
     * Config class cloner
     */
    private function __clone() { }

    /**
     * Return instance of Config class
     *
     * @param string $configPath Path to config file
     * @return Config
     */
    public static function instantiate($configPath)
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className($configPath);
        }
        return self::$instance;
    }

    /**
     * Get config item
     *
     * @param string $field Item name
     * @return bool|string
     */
    public function __get($field) {
        if (array_key_exists($field, $this->config)) {
            return $this->config[$field];
        } else {
            return false;
        }
    }
}