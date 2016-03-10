<?php

namespace Framework\Model;

use Framework\DI\Service;
use PDO;

/**
 * Class ActiveRecord to implements AR pattern
 *
 * @package Framework\Model
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class ActiveRecord
{
    /**
     * @var bool True if it is new row
     */
    private $newRow = false;

    /**
     * @var array Fields from DB
     */
    private $oldFields = [];

    /**
     * ActiveRecord class contructor, uses for creating instances of model
     * with data from DB
     *
     * @param array $args Data from DB
     */
    public function __construct($args = null)
    {
        if (!is_null($args)) {
            foreach ($args as $key => $value) {
                $this->$key = $value;
            }
            $this->oldFields = get_object_vars($this);
        } else {
            $this->newRow = true;
        }
    }

    /**
     * Save object to DB
     */
    public function save() {
        /** @var PDO $db */
        $db = Service::get('db');
        $calledClass = get_called_class();
        $query = new QueryBuilder($calledClass::getTable());

        if ($this->newRow) {
            $fields = get_object_vars($this);
            $sql = $query->insert(array_keys($fields))->getQuery();
        } else {
            $fields = array_diff_assoc(get_object_vars($this), $this->oldFields);
            $sql = $query->update(array_keys($fields))->getQuery();
        }

        $state = $db->prepare($sql);
        $state->execute($fields);
    }

    public function delete()
    {
        /** @var PDO $db */
        $db = Service::get('db');
        $calledClass = get_called_class();
        $query = new QueryBuilder($calledClass::getTable());
        $stat = $db->prepare($query->delete($this->id)->getQuery());
        $stat->execute();
    }

    /**
     * Dynamic creating SQL from function name
     *
     * @param string $method Function name
     * @param array $values Function arguments
     * @return bool|object Selected object or false
     */
    public static function __callStatic($method, $values)
    {
        $calledClass = get_called_class();
        $query = new QueryBuilder($calledClass::getTable());

        /**
         * @var PDO $db
         */
        $db = Service::get('db');

        if (substr($method, 0, 6) === 'findBy') {
            $findStr = substr($method, 6);
            $conditions = $query->makeConditions($findStr, $values);
            $query->where($conditions);

            $state = $db->prepare($query->getQuery());
            $state->execute($values);
            $data = $state->fetch(PDO::FETCH_ASSOC);

            return ($data === false) ? false : new $calledClass($data);
        }

        if (substr($method, 0, 4) === 'find') {
            if (is_int($values[0])) {
                return self::findById($values[0]);
            }

            $result = [];
            if ($values[0] == 'all') {
                $state = $db->prepare($query->getQuery());
                $state->execute();
                $rows = $state->fetchAll(PDO::FETCH_ASSOC);
                foreach($rows as $row) {
                    $result[] = new $calledClass($row);
                }
            }
            return $result;
        }

        return false;
    }
}