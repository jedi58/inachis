<?php

namespace Inachis\Component\Common\Form;

use Inachis\Component\Common\Exception\FormBuilderConfigurationException;
/**
 *
 */
abstract class FormComponent extends AbstractFormType
{
	/**
	 * @var string The label for the form component
	 */
	protected $label;
	/**
	 * @var string The value for the element
	 */
	protected $value;
	/**
	 * @var bool Flag indicating if it should allow user-input
	 */
	protected $readOnly = false;
	/**
	 * @var bool Flag indicating if the field is required
	 */
	protected $required = false;
	/**
	 * @var bool Flag indicating if the field is disabled
	 */
	protected $disabled = false;
	/**
	 * @var string HTML element to wrap the field in if set
	 */
	protected $wrapperElement = 'p';
	/**
	 * @var string[] The CSS classes to apply to the wrapping elements if applicable
	 */
	protected $wrapperClasses = array();
	/**
	 * Default constructor for {@link FormComponent}
	 * @param string $name The name of the form component
	 * @param string $label The label for the form component
	 * @param string $value The value for the form component
	 * @param string $id The ID of the form component
	 */
	public function __construct($name = '', $label = '', $value = '', $id = '')
	{
		$this->setName($name);
		$this->setLabel($label);
		$this->setValue($value);
		$this->setId(!empty($id) ? $id : $name);
	}
	/**
	 * Sets the value of {@link $label}
	 * @param string $value The text to use for the label
	 * @return FormComponent Returns a reference to itself
	 */
	public function setLabel($value)
	{
		$this->label = $value;
        return $this;
	}
	/**
	 * Returns the value of {@link label}
	 * @return string The value of the property
	 */
	public function getLabel()
	{
		return $this->label;
	}
	/**
	 * Sets the value of {@link $value}
	 * @param string $value The value for the form element
	 * @return FormComponent Returns a reference to itself
	 */
	public function setValue($value)
	{
		$this->value = $value;
        return $this;
	}
	/**
	 * Returns the value of {@link value}
	 * @return string The value of the property
	 */
	public function getValue()
	{
		return $this->value;
	}
	/**
	 * Sets the value of {@link $readOnly}
	 * @param bool $value The value to set the property to
	 * @return FormComponent Returns a reference to itself
	 */
	public function setReadOnly($value)
	{
		$this->readOnly = (bool) $value;
        return $this;
	}
	/**
	 * Returns the value of {@link readOnly}
	 * @return bool The value of the property
	 */
	public function getReadOnly()
	{
		return $this->readOnly;
	}
	/**
	 * Sets the value of {@link $required}
	 * @param bool $value The value to set the property to
	 * @return FormComponent Returns a reference to itself
	 */
	public function setRequired($value)
	{
		$this->required = (bool) $value;
        return $this;
	}
	/**
	 * Returns the value of {@link required}
	 * @return bool The value of the property
	 */
	public function getRequired()
	{
		return $this->required;
	}
	/**
	 * Sets the value of {@link $disabled}
	 * @param bool $value The value to set the property to
	 * @return FormComponent Returns a reference to itself
	 */
	public function setDisabled($value)
	{
		$this->disabled = (bool) $value;
        return $this;
	}
	/**
	 * Returns the value of {@link disabled}
	 * @return bool The value of the property
	 */
	public function getDisabled()
	{
		return $this->disabled;
	}
	/**
	 * Sets the value of {@link $wrapperElement}
	 * @param string $value The value to set the property to
	 * @return FormComponent Returns a reference to itself
	 */
	public function setWrapperElement($value)
	{
		$this->wrapperElement = $value;
	}
	/**
	 * Returns the value of {@link wrapperElement}
	 * @return string The value of the property
	 */
	public function getWrapperElement()
	{
		return $this->wrapperElement;
	}
	/**
	 * Sets the value of {@link $wrapperClasses}
	 * @param string[] $value The value to set the property to
	 * @return FormComponent Returns a reference to itself
	 */
	public function setWrapperClasses($value)
	{
		if ($this->wrapperElement === '') {
			throw new FormBuilderConfigurationException('Cannot apply wrapping CSS with no wrapper element');
		}
		$this->wrapperClasses = $value;
		return $this;
	}
	/**
	 * Returns the value of {@link wrapperClasses}
	 * @return string[] The value of the property
	 */
	public function getWrapperClasses()
	{
		return $this->wrapperClasses;
	}
	/**
	 * Adds a value to {@link $wrapperClasses}
	 * @param string $value The CSS class to add
	 * @return FormComponent Returns a reference to itself
	 */
	public function addWrapperClass($value)
	{
		if ($this->wrapperElement === '') {
			throw new FormBuilderConfigurationException('Cannot apply wrapping CSS with no wrapper element');
		}
		$this->wrapperClasses[] = $value;
		return $this;
	}
}
