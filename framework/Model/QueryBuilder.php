<?php

namespace Framework\Model;

/**
 * Class QueryBuilder for building SQL
 *
 * @package Framework\Model
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class QueryBuilder
{
    /**
     * @var string SQL-code for query
     */
    private $query = '';
    /**
     * @var array Options for building query
     */
    private $options = [];
    /**
     * @var string Query-mode (select, insert, update, delete)
     */
    private $mode = 'select';

    /**
     * QueryBuilder class constructor
     *
     * @param string $table Table name
     */
    public function __construct($table)
    {
        $this->options['table'] = $table;
        $this->options['fields'] = '*';
    }

    /**
     * Make insert query
     *
     * @param string|array $fields Fields to insert
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function insert($fields)
    {
        $this->fields($fields);
        $this->mode = 'insert';
        return $this;
    }

    /**
     * Make update query
     *
     * @param string|array $fields Fields to update
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function update($fields)
    {
        $this->fields($fields);
        $this->mode = 'update';
        return $this;
    }

    /**
     * Make delete query
     *
     * @param int $id Row id
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function delete($id)
    {
        $this->mode = 'delete';
        $this->options['where'] = '`id` = ' . $id;
        return $this;
    }

    /**
     * Set fields to working
     *
     * @param string|array $fields Fields to working
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function fields($fields)
    {
        if (is_array($fields)) {
            $this->options['fields'] = implode(', ', $fields);
        } else $this->options['fields'] = $fields;
        return $this;
    }

    /**
     * Set limit option
     *
     * @param int $limit Number of rows to returning
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function limit($limit)
    {
        $this->options['limit'] = $limit;
        return $this;
    }

    /**
     * Set offset option
     *
     * @param int $offset Number of rows to offset
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function offset($offset)
    {
        $this->options['offset'] = $offset;
        return $this;
    }

    /**
     * Set ordering
     *
     * @param string $fieldName Field for ordering
     * @param string $type Type of ordering
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function order($fieldName, $type = null)
    {
        $this->options['order']['fieldName'] = $fieldName;
        if (!is_null($type))
            $this->options['order']['type'] = $type;
        return $this;
    }

    /**
     * Set conditions
     *
     * @param string $where "where" string
     * @return QueryBuilder $this Instance of QueryBuilder
     */
    public function where($where)
    {
        $this->options['where'] = $where;
        return $this;
    }

    /**
     * Return SQL
     *
     * @return string Prepared SQL
     */
    public function getQuery()
    {
        if ($this->mode === 'select') {
            $this->query = 'SELECT ' . $this->options['fields']
                . ' FROM ' . $this->options['table'];

            if (array_key_exists('where', $this->options))
                $this->query .= ' WHERE ' . $this->options['where'];

            if (array_key_exists('order', $this->options)) {
                $this->query .= ' ORDER BY ' . implode(', ', $this->options['order']['fieldName']);
                if (array_key_exists('type', $this->options['order'])) {
                    $this->query .= $this->options['order']['type'];
                }
            }

            if (array_key_exists('limit', $this->options)) {
                $this->query .= ' LIMIT ';
                if (array_key_exists('offset', $this->options)) {
                    $this->query .= $this->options['offset'] . ', '
                        . $this->options['limit'];
                } else {
                    $this->query .= $this->options['limit'];
                }
            }
        }
        elseif ($this->mode === 'insert') {
            $fields = explode(',', $this->options['fields']);
            foreach ($fields as $field) {
                $binds[] = ':' . trim($field);
            }
            $bind = implode(', ', $binds);
            $this->query = 'INSERT INTO ' . $this->options['table']
                . '(' . $this->options['fields'] . ') VALUES (' . $bind .')';

        }
        elseif ($this->mode === 'update') {
            $this->query = 'UPDATE ' . $this->options['table'] . ' SET ';
            foreach ($this->options['fields'] as $field) {
                //$parts[] =
            }
        }
        elseif ($this->mode === 'delete') {
            $this->query = 'DELETE FROM ' . $this->options['table'];
            if (array_key_exists('where', $this->options))
                $this->query .= ' WHERE ' . $this->options['where'];
        }

        return $this->query;
    }

    /**
     * Make conditions SQL from attributes and his values
     *
     * @param string $attributes Attributes
     * @param array $values Values to binding
     * @return array|string Array of conditions SQL and values or blank string
     */
    public function makeConditions($attributes, $values)
    {
        if (!$attributes) return '';
        $parts      = preg_split('/(And|Or)/i', $attributes, NULL, PREG_SPLIT_DELIM_CAPTURE);
        $condition  = '';

        $j = 0;
        foreach($parts as $part) {
            if ($part == 'And') {
                $condition .= ' AND ';
            } elseif ($part == 'Or') {
                $condition .= ' OR ';
            } else {
                $part = strtolower($part);
                if (($j < count($values)) && (!is_null($values[$j]))) {
                    $bind = is_array($values[$j]) ? ' IN(?)' : '=?';
                } else {
                    $bind = ' IS NULL';
                }
                $condition .= self::quote($part) . $bind;
                $j++;
            }
        }
        return $condition;
    }

    /**
     * Quoting a literal
     *
     * @param string $name Literal for quoting
     * @return string Quoted string
     */
    private static function quote($name)
    {
        $name = trim($name, '`');
        return '`'.$name.'`';
    }
}