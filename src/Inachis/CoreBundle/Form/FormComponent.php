<?php

namespace Inachis\Component\CoreBundle\Form;

use Inachis\Component\CoreBundle\Exception\FormBuilderConfigurationException;

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
     * @var string An ID to assign to the label for use by aria, etc.
     */
    protected $labelId;
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
     * @var string[] An array of aria attributes for the form elements (exclusing aria- prefix)
     */
    protected $ariaAttributes = array();
    /**
     * Default constructor for {@link FormComponent}
     * @param string[] $properties The properties to apply to the form component
     * @throws FormBuilderConfigurationException
     */
    public function __construct($properties = array())
    {
        if (!is_array($properties)) {
            throw new FormBuilderConfigurationException('Properties of component must be an array');
        }
        foreach ($properties as $key => $value) {
            $setterFunction = 'set'.ucfirst($key);
            if (!method_exists($this, $setterFunction)) {
                throw new FormBuilderConfigurationException('Setter function does not exist for ' . $setterFunction);
            }
            $this->$setterFunction($value);
        }
        if (!empty($this->name) && empty($this->id)) {
            $this->setId($this->name);
        }
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
     * Sets the value of {@link $labelId}
     * @param string $value The ID to use for the label
     * @return FormComponent Returns a reference to itself
     */
    public function setLabelId($value)
    {
        $this->labelId = $value;
        return $this;
    }
    /**
     * Returns the value of {@link labelId}
     * @return string The value of the property
     */
    public function getLabelId()
    {
        return $this->labelId;
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
     * @throws FormBuilderConfigurationException
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
     * @throws FormBuilderConfigurationException
     */
    public function addWrapperClass($value)
    {
        if ($this->wrapperElement === '') {
            throw new FormBuilderConfigurationException('Cannot apply wrapping CSS with no wrapper element');
        }
        $this->wrapperClasses[] = $value;
        return $this;
    }
    /**
     * Sets the contents of the {@link ariaAttributes} array
     * @param string[] $value The value to set the property to
     * @return FormComponent Returns a reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setAriaAttributes($value)
    {
        if (!is_array($value)) {
            throw new FormBuilderConfigurationException('Aria atttributes must be an array');
        }
        $this->ariaAttributes = $value;
        return $this;
    }
    /**
     * Returns the array of Aria attributes
     * @return string[] The array of Aria attributes
     */
    public function getAriaAttributes()
    {
        return $this->ariaAttributes;
    }
    /**
     * Adds a new aria attribute to {@link ariaAttributes}
     * @param string $value The aria attribute to add
     * @return FormComponent Returns a reference to itself
     */
    public function addAriaAttribute($value)
    {
        $this->ariaAttributes[] = $value;
        return $this;
    }
}
