<?php

namespace Inachis\Component\CoreBundle\Form\Fields;

use Inachis\Component\CoreBundle\Form\FormComponent;
use Inachis\Component\CoreBundle\Exception\FormBuilderConfigurationException;

/**
 * Object for handling `type="checkbox"` fields
 */
class ChoiceType extends FormComponent
{
    /**
     * The allowed input types for the DateTime field
     */
    const ALLOWED_CHOICES_TYPES = array('checkbox', 'radio');
    /**
     * @var string The type of DateTime field being used
     */
    protected $type = 'checkbox';
    /**
     * @var bool Flag indicating if the field should automatically take focus
     *           on page load
     */
    protected $autoFocus;
    /**
     * @var bool Flag indicating if the checkbox state is ticked
     */
    protected $checked;
    /**
     * Sets the type of choice question
     * @param string $value The type of choice question being used
     * @return ChoiceType Returns a reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setType($value)
    {
        if (!in_array($value, self::ALLOWED_CHOICES_TYPES)) {
            throw new FormBuilderConfigurationException(sprintf(
                '%s is not a valid choice type. Allowed are: %s',
                $value,
                implode(', ', self::ALLOWED_CHOICES_TYPES)
            ));
        }
        $this->type = $value;
        return $this;
    }
    /**
     * Sets the value of {@link autoFocus}
     * @param bool $value The value to set the property to
     * @return ChoiceType Returns a reference to itself
     */
    public function setAutoFocus($value)
    {
        $this->autoFocus = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link checked}
     * @param bool $value The value to set the property to
     * @return ChoiceType Returns a reference to itself
     */
    public function setChecked($value)
    {
        $this->checked = (bool) $value;
        return $this;
    }
    /**
     * Returns the type of field
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
    /**
     * Returns the value of {@link checked}
     * @return bool $value The properties value
     */
    public function getChecked()
    {
        return $this->checked;
    }
}
