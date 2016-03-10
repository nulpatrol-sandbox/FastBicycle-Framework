<?php

namespace Framework\Model;

use Framework\Exception\DatabaseException;
use Framework\DI\Service;

class Connection
{
    private $connection;
    private static $instance;

    public static function instantiate()
    {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className();
        }
        return self::$instance;
    }

    private  function __construct()
    {
        $dbConfig = Service::get('config')->pdo;
        try {
            $this->connection = new \PDO($dbConfig['dns'], $dbConfig['user'], $dbConfig['password']);
        } catch(\PDOException $e) {
            throw new DatabaseException('');
        }
    }

    public function execute($sql, $values = []) {
        try {
            if (!($query = $this->connection->prepare($sql))) {
                throw new DatabaseException($this);
            }
        } catch (\PDOException $e) {
            throw new DatabaseException('');
        }

        $query->setFetchMode(\PDO::FETCH_OBJ);
        
        try {
            if (!$query->execute($values)) {
                throw new DatabaseException('');
            }
        } catch (\PDOException $e) {
            throw new DatabaseException('');
        }
        return $query->fetchAll();
    }

}