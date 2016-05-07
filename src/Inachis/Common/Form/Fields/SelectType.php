<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
/**
 * Object for handling select fields
 */
class SelectType extends AbstractSelectType
{
    /**
     * @var bool Flag indicating if the field should automatically take focus
     *           on page load
     */
    protected $autoFocus = false;
    /**
     * @var bool Flag indicating if the select box should allow multiple selections
     */
    protected $multiple;
    /**
     * @var int The The number of visible options
     */
    protected $size;
    /**
     * Returns the type of field
     * @return string The field type
     */
    public function getType()
    {
        return 'select';
    }
    /**
     * Sets the value of {@link autoFocus}
     * @param bool $value The value to set the property to
     * @return SelectType Returns the current object
     */
    public function setAutoFocus($value)
    {
        $this->autoFocus = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link multiple}
     * @param bool $value The value to set the property to
     * @return SelectType Returns the current object
     */
    public function setMultiple($value)
    {
        $this->multiple = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link size}
     * @param int $value The value to set the property to
     * @return SelectType Returns the current object
     */
    public function setSize($value)
    {
        $this->size = (int) $value;
        return $this;
    }
    /**
     * Returns the value of {@link autoFocus}
     * @return bool The properties value
     */
    public function getAutoFocus()
    {
        return $this->autoFocus;
    }
    /**
     * Returns the value of {@link multiple}
     * @return bool The properties value
     */
    public function getMultiple()
    {
        return $this->multiple;
    }
    /**
     * Returns the value of {@link size}
     * @return int The properties value
     */
    public function getSize()
    {
        return $this->size;
    }
}
