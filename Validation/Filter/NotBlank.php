<?php

namespace Framework\Validation\Filter;

/**
 * Class NotBlank which checking field blanking
 *
 * @package Framework\Validation\Filter
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 */
class NotBlank
{
    /**
     * Checking field for valid length
     *
     * @param string $field Field value
     * @return bool|string True or error message
     */
    public function check($field)
    {
        if ($field == '') {
            return 'Must be not blank';
        } else {
            return true;
        }
    }
}