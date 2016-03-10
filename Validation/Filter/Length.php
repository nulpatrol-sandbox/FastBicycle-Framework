<?php

namespace Framework\Validation\Filter;

/**
 * Class Length which checking length of field
 *
 * @package Framework\Validation\Filter
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class Length
{
    /**
     * Length class constructor
     *
     * @param int $min Minimal length
     * @param int $max Maximal length
     */
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Checking field for valid length
     *
     * @param string $field Field value
     * @return bool|string True or error message
     */
    public function check($field)
    {
        if ((($this->min <= strlen($field)) && ($this->max >= strlen($field))) == false) {
            return 'Length must be more then ' . $this->min . ' symbols and less then ' .
                $this->max . ' symbols';
        }
        return true;
    }
}