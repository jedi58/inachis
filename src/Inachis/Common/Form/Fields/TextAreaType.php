<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
use Inachis\Component\Common\Exception\FormBuilderConfigurationException;

/**
 * Object for handling textarea fields
 */
class TextAreaType extends FormComponent
{
    /**
     * The allowed wrap types for a textarea
     */
    const ALLOWED_TEXTAREA_WRAP = array('hard', 'soft');
    /**
     * @var bool Flag indicating if the field should automatically take focus
     *           on page load
     */
    protected $autoFocus = false;
    /**
     * @var int The number of columns for the textarea
     */
    protected $cols;
    /**
     * @var int The maximum number of characters to allow
     */
    protected $maxLength;
    /**
     * @var string Placeholder text for the field
     */
    protected $placeholder;
    /**
     * @var int The number of rows for the textarea
     */
    protected $rows;
    /**
     * @var string The type of wrapping to use
     */
    protected $wrap;
    /**
     * Returns the type of field being used
     * @return string The type of field being used
     */
    public function getType()
    {
        return 'textarea';
    }
    /**
     * Sets the value of {@link autoFocus}
     * @param bool $value The value to set the property to
     * @return TextAreaType Returns a reference to itself
     */
    public function setAutoFocus($value)
    {
        $this->autoFocus = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link cols}
     * @param int $value The value to set the property to
     * @return TextAreaType Returns a reference to itself
     */
    public function setCols($value)
    {
        $this->cols = (int) $value;
        return $this;
    }
    /**
     * Sets the value of {@link maxLength}
     * @param int $value The value to set the property to
     * @return TextAreaType Returns a reference to itself
     */
    public function setMaxLength($value)
    {
        $this->maxLength = (int) $value;
        return $this;
    }
    /**
     * Sets the value of {@link placeholder}
     * @param string $value The value to set the property to
     * @return TextAreaType Returns a reference to itself
     */
    public function setPlaceholder($value)
    {
        $this->placeholder = $value;
        return $this;
    }
    /**
     * Sets the value of {@link step}
     * @param int $value The value to set the property to
     * @return TextAreaType Returns a reference to itself
     */
    public function setRows($value)
    {
        $this->step = (int) $value;
        return $this;
    }
    /**
     * Sets the value of {@link wrap}
     * @param int $value The value to set the property to
     * @return TextAreaType Returns a reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setWrap($value)
    {
        if (!in_array($value, self::ALLOWED_TEXTAREA_WRAP)) {
            throw new FormBuilderConfigurationException(sprintf(
                '%s is not a valid wrap mode for a textarea. Allowed are: %s',
                $value,
                implode(', ', self::ALLOWED_TEXTAREA_WRAP)
            ));
        }
        $this->wrap = $value;
        return $this;
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
     * Returns the value of {@link cols}
     * @return int $value The properties value
     */
    public function getCols()
    {
        return $this->cols;
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
     * Returns the value of {@link placeholder}
     * @return string $value The properties value
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }
    /**
     * Returns the value of {@link rows}
     * @return int $value The properties value
     */
    public function getRows()
    {
        return $this->rows;
    }
    /**
     * Returns the value of {@link wrap}
     * @return string $value The properties value
     */
    public function getWrap()
    {
        return $this->wrap;
    }
}
