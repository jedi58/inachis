<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
use Inachis\Component\Common\Exception\FormBuilderConfigurationException;

/**
 * Object for handling `type="button|submit"` buttons
 */
class ButtonType extends FormComponent
{
    /**
     * The allowed input types for the DateTime field
     */
    const ALLOWED_BUTTON_TYPES = array('button', 'submit');
    /**
     * @var string The type of DateTime field being used
     */
    protected $type = 'button';
    /**
     * @var bool Flag indicating if the field should automatically take focus
     *           on page load
     */
    protected $autoFocus;
    /**
     * Sets the type of button
     * @param string $value The type of button being used
     * @return ButtonType Returns a reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setType($value)
    {
        if (!in_array($value, self::ALLOWED_BUTTON_TYPES)) {
            throw new FormBuilderConfigurationException(sprintf(
                '%s is not a valid button type. Allowed are: %s',
                $value,
                implode(', ', self::ALLOWED_BUTTON_TYPES)
            ));
        }
        $this->type = $value;
        return $this;
    }
    /**
     * Sets the value of {@link autoFocus}
     * @param bool $value The value to set the property to
     * @return ButtonType Returns a reference to itself
     */
    public function setAutoFocus($value)
    {
        $this->autoFocus = (bool) $value;
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
     * Returns the value of {@link autoFocus}
     * @return bool $value The properties value
     */
    public function getAutoFocus()
    {
        return $this->autoFocus;
    }
}
