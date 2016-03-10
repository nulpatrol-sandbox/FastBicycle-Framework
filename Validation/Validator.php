<?php

namespace Framework\Validation;

/**
 * Class Validator for validation input data
 *
 * @author Rost Khanyukov <rost.khanyukov@gmail.com>
 * @package Framework\Validation
 */
class Validator
{
    /**
     * @var array Rules for checking
     */
    private $rules;
    /**
     * @var ActiveRecord Instance of class which implements ActiveRecord pattern
     */
    private $ARClass;
    /**
     * @var array Errors
     */
    private $errors;

    /**
     * Validator class constructor
     *
     * @param ActiveRecord $ARClass Class for validation
     */
    public function __construct($ARClass)
    {
        $this->ARClass = $ARClass;
        $this->rules = $this->ARClass->getRules();
    }

    /**
     * Check object with rules
     *
     * @return bool Is object valid
     */
    public function isValid()
    {
        foreach ($this->rules as $fieldKey => $fieldRules) {
            foreach ($fieldRules as $rule) {
                $result = $rule->check($this->ARClass->$fieldKey);
                if ($result === true) {
                    continue;
                } else {
                    $this->errors[$fieldKey] = $result;
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Return errors array
     *
     * @return array Errors
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
