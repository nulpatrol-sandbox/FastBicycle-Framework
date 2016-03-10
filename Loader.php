<?php
/**
 * Class Loader implements autoloading
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Loader
{
    /**
     * @var array $namespacePaths Array with namespaces paths
     */
    public static $namespacePaths = array();

    /**
     * @var Loader Instance of loader class
     */
    private static $instance;

    /**
     * Loader class cloner
     */
    private function __clone() { }

    /**
     * Loader class constructor
     */
    private function __construct() {
        spl_autoload_register(array($this, 'loadClass'));
        self::addNamespacePath('Framework\\', __DIR__);
    }

    /**
     * Instantiate loader
     *
     * @return Loader instance
     */
    public static function instantiate()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className();
        }
        return self::$instance;
    }

    /**
     * Resolve path to finding namespace
     *
     * @param string $namespace Namespace for resolving
     * @param string $path Path to namespace root directory
     */
    public static function addNamespacePath($namespace, $path)
    {
        $namespace = trim($namespace, '\\') . '\\';
        self::$namespacePaths[$namespace] = rtrim($path, '/') . '/';
    }

    /**
     * Get path to namespace
     *
     * @param string $namespace Namespace for returning path
     * @return string Path
     */
    public static function getNamespacePath($namespace)
    {
        if (array_key_exists($namespace, self::$namespacePaths)) {
            return self::$namespacePaths[$namespace];
        } else {
            return false;
        }
    }

    /**
     * Require file with implementation of class
     *
     * @param string $className Full class name
     * @return bool
     */
    public static function loadClass($className)
    {
        $namespace = $className;
        while (false !== ($pos = strrpos($namespace, '\\'))) {
            $namespace = substr($className, 0, $pos + 1);
            $classPath = substr($className, $pos + 1);

            if (isset(self::$namespacePaths[$namespace])) {
                $fileName = self::$namespacePaths[$namespace] . str_replace('\\', '/', $classPath) . '.php';
                if (file_exists($fileName)) {
                    require_once($fileName);
                    return true;
                }
            }

            $namespace = rtrim($namespace, '\\');
        }
        return false;
    }
}

Loader::instantiate();