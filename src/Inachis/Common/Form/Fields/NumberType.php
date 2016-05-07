<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
use Inachis\Component\Common\Exception\FormBuilderConfigurationException;

/**
 * Object for handling `type="date|datetime|datetime-local"` fields
 */
class NumberType extends FormComponent
{
    /**
     * The allowed input types for the number field
     */
    const ALLOWED_NUMBER_TYPES = array(
        'date',
        'datetime',
        'datetime-local',
        'month',
        'number',
        'range',
        'time'
    );
    /**
     * @var string The type of number field being used
     */
    protected $type = 'number';
    /**
     * @var bool Flag indicating if the field should allow autocomplete
     */
    protected $autoComplete = true;
    /**
     * @var bool Flag indicating if the field should automatically take focus
     *           on page load
     */
    protected $autoFocus = false;
    /**
     * @var int The maximum date to allow
     */
    protected $max;
    /**
     * @var int The minimum date to allow
     */
    protected $min;
    /**
     * @var int The The number of days to step by
     */
    protected $step;
    /**
     * Sets the type of field
     * @param string $value The type of field being used
     * @return NumberType Returns a reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setType($value)
    {
        if (!in_array($value, self::ALLOWED_NUMBER_TYPES)) {
            throw new FormBuilderConfigurationException(sprintf(
                '%s is not a valid number field type. Allowed: %s',
                $value,
                implode(', ', self::ALLOWED_NUMBER_TYPES)
            ));
        }
        $this->type = $value;
        return $this;
    }
    /**
     * Sets the value of {@link autoComplete}
     * @param bool $value The value to set the property to
     * @return NumberType Returns a reference to itself
     */
    public function setAutoComplete($value)
    {
        $this->autoComplete = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link autoFocus}
     * @param bool $value The value to set the property to
     * @return NumberType Returns a reference to itself
     */
    public function setAutoFocus($value)
    {
        $this->autoFocus = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link max}
     * @param string $value The value to set the property to
     * @return NumberType Returns a reference to itself
     */
    public function setMax($value)
    {
        $this->max = $value;
        return $this;
    }
    /**
     * Sets the value of {@link min}
     * @param string $value The value to set the property to
     * @return NumberType Returns a reference to itself
     */
    public function setMin($value)
    {
        $this->min = $value;
        return $this;
    }
    /**
     * Sets the value of {@link step}
     * @param int $value The value to set the property to
     * @return NumberType Returns a reference to itself
     */
    public function setStep($value)
    {
        $this->step = (int) $value;
        return $this;
    }
    /**
     * Returns the type of DateTime field being used
     * @return string The type of field being used
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Returns the value of {@link autoComplete}
     * @return bool $value The properties value
     */
    public function getAutoComplete()
    {
        return $this->autoComplete;
    }
    /**
     * Returns the value of {@link autoFocus}
     * @return bool $value The properties value
     */
    public function getAutoFocus()
    {
        return $this->autoFocus;
    }
    /**
     * Returns the value of {@link max}
     * @return int $value The properties value
     */
    public function getMax()
    {
        return $this->max;
    }
    /**
     * Returns the value of {@link min}
     * @return int $value The properties value
     */
    public function getMin()
    {
        return $this->min;
    }
    /**
     * Returns the value of {@link step}
     * @return int $value The properties value
     */
    public function getStep()
    {
        return $this->step;
    }
}
