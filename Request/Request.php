<?php

namespace Framework\Request;

/**
 * Class Request for validation input data
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Request
{
    /**
     * Request class constructor
     */
    public function __construct() {
        $this->sanitizeGlobals();
    }

    /**
     * Return an item from $_POST
     *
     * @param string $key Item name
     * @return string
     */
    public function post($key)
    {
        if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }
        return false;
    }

    /**
     * Return an item from $_SERVER
     *
     * @param string $key Item name
     * @return string
     */
    public function server($key)
    {
        if (array_key_exists($key, $_SERVER)) {
            return $_SERVER[$key];
        }
        return false;
    }

    /**
     * Return true if request method is POST
     *
     * @return bool
     */
    public function isPost() {
        return $this->server('REQUEST_METHOD') == 'POST';
    }

    /**
     * Clear global arrays GET, POST, SERVER
     */
    private function sanitizeGlobals() {
        if (is_array($_POST) && count($_POST) > 0) {
            foreach ($_POST as $key => $val) {
                if (preg_match('/^[a-z0-9:_\/|-]+$/i', $key)) {
                    $_POST[$key] = $this->cleanInputData($val);
                }
            }
        }

        if (is_array($_GET) && count($_GET) > 0) {
            foreach ($_GET as $key => $val) {
                if (preg_match('/^[a-z0-9:_\/|-]+$/i', $key)) {
                    $_GET[$key] = $this->cleanInputData($val);
                }
            }
        }

        if (is_array($_SERVER) && count($_SERVER) > 0) {
            foreach ($_SERVER as $key => $val) {
                if (preg_match('/^[a-z0-9:_\/|-]+$/i', $key)) {
                    $_SERVER[$key] = $this->cleanInputData($val);
                }
            }
        }
    }

    /**
     * Clean variable
     *
     * @param array|string $param Variable for cleaning
     * @return array|string
     */
    private  function cleanInputData($param) {
        $nonDisplayables = array('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S');

        if (is_array($param)) {
            $new_array = array();
            foreach (array_keys($param) as $key) {
                if (preg_match('/^[a-z0-9:_\/|-]+$/i', $key)) {
                    $new_array[$key] = $this->cleanInputData($param[$key]);
                }
            }
            return $new_array;
        }

        do {
            $param = preg_replace($nonDisplayables, '', $param, -1, $count);
        } while ($count);

        return $param;
    }

}