<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
/**
 * Object for handling select fields
 */
class SelectOptionType extends FormComponent
{
    /**
     * @var string The `innerHTML` for the option
     */
    protected $label;
    /**
     * @var bool Flag indicating if this option is selected
     */
    protected $selected = false;
    /**
     * @var string $value The value for the option
     */
    protected $value;
    /**
     * Default constructor for {@link SelectOptionType}
     * @param string $label The `innerHTML` for the option
     * @param string $value The value for the option
     * @param bool $selected Flag indicating if the option is selected by default
     */
    public function __construct($label = '', $value = '', $selected = false)
    {
        $this->setLabel($label);
        $this->setValue($value);
        $this->setSelected($selected);
    }
    /**
     * Returns the type of field
     * @return string The field type
     */
    public function getType()
    {
        return 'option';
    }
    /**
     * Sets the value of {@link label}
     * @param string $label The value to set the property to
     * @return SelectOptionType Returns the current object
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
    /**
     * Sets the value of {@link selected}
     * @param bool $selected The value to set the property to
     * @return SelectOptionType Returns the current object
     */
    public function setSelected($selected)
    {
        $this->selected = (bool) $selected;
        return $this;
    }
    /**
     * Sets the value of {@link value}
     * @param string $value The value to set the property to
     * @return SelectOptionType Returns the current object
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
    /**
     * Returns the value of {@link label}
     * @return bool The properties value
     */
    public function getLabel()
    {
        return $this->label;
    }
    /**
     * Returns the value of {@link selected}
     * @return bool The properties value
     */
    public function getSelected()
    {
        return $this->selected;
    }
    /**
     * Returns the value of {@link value}
     * @return int The properties value
     */
    public function getValue()
    {
        return $this->value;
    }
}
