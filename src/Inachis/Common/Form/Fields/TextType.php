<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
use Inachis\Component\Common\Exception\FormBuilderConfigurationException;

/**
 * Object for handling `type="text"` fields
 */
class TextType extends FormComponent
{
    /**
     * The allowed input types for the text field
     */
    const ALLOWED_TEXT_TYPES = array('email', 'password', 'search', 'text', 'url');
    /**
     * @var string The type of DateTime field being used
     */
    protected $type = 'text';
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
     * @var int The maximum length of input for the field
     */
    protected $maxLength = 0;
    /**
     * @var string A regular expression for validating the field content
     */
    protected $pattern;
    /**
     * @var string Placeholder text for the field
     */
    protected $placeholder;
    /**
     * @var int The width of the field based on number of characters
     */
    protected $size;
    /**
     * Sets the type of field
     * @param string $value The type of field being used
     * @return TextType Returns a reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setType($value)
    {
        if (!in_array($value, self::ALLOWED_TEXT_TYPES)) {
            throw new FormBuilderConfigurationException(sprintf(
                '%s is not a valid text field type. Allowed are: %s',
                $value,
                implode(', ', self::ALLOWED_TEXT_TYPES)
            ));
        }
        $this->type = $value;
        return $this;
    }
    /**
     * Sets the value of {@link autoComplete}
     * @param bool $value The value to set the property to
     * @return TextType Returns a reference to itself
     */
    public function setAutoComplete($value)
    {
        $this->autoComplete = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link autoFocus}
     * @param bool $value The value to set the property to
     * @return TextType Returns a reference to itself
     */
    public function setAutoFocus($value)
    {
        $this->autoFocus = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link maxLength}
     * @param int $value The value to set the property to
     * @return TextType Returns a reference to itself
     */
    public function setMaxLength($value)
    {
        $this->maxLength = (int) $value;
        return $this;
    }
    /**
     * Sets the value of {@link maxLength}
     * @param string $value The value to set the property to
     * @return TextType Returns a reference to itself
     */
    public function setPattern($value)
    {
        $this->pattern = $value;
        return $this;
    }
    /**
     * Sets the value of {@link placeholder}
     * @param string $value The value to set the property to
     * @return TextType Returns a reference to itself
     */
    public function setPlaceholder($value)
    {
        $this->placeholder = $value;
        return $this;
    }
    /**
     * Sets the value of {@link size}
     * @param int $value The value to set the property to
     * @return TextType Returns a reference to itself
     */
    public function setSize($value)
    {
        $this->size = (int) $value;
        return $this;
    }
    /**
     * Returns the type of text field being used
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
     * Returns the value of {@link maxLength}
     * @return int $value The properties value
     */
    public function getMaxLength()
    {
        return $this->maxLength;
    }
    /**
     * Returns the value of {@link pattern}
     * @return string $value The properties value
     */
    public function getPattern()
    {
        return $this->pattern;
    }
    /**
     * Returns the value of {@link placeholder}
     * @return string $value The properties value
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }
    /**
     * Returns the value of {@link size}
     * @return int $value The properties value
     */
    public function getSize()
    {
        return $this->size;
    }
}
