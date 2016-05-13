<?php

namespace Inachis\Component\CoreBundle\Form;

use Inachis\Component\CoreBundle\Exception\FormBuilderConfigurationException;

/**
 * @todo Add CSRF protection
 */
class FormBuilder extends AbstractFormType
{
    /**
    * @var string[] The accepted HTTP/1.1 request methods
    */
    private $allowedMethods = array(
        'DELETE',
        'GET',
        'POST',
        'PUT'
    );
    /**
     * @var string[] The allowed enctype values for the form
     */
    private $allowedEncTypes = array(
        'application/x-www-form-urlencoded',
        'multipart/form-data',
        'text/plain'
    );
    /**
     * @var string The method to use for the form
     */
    protected $method = 'POST';
    /**
     * @var string The action for the form
     */
    protected $action;
    /**
     * @var bool Flag indicating of the form
     */
    protected $autoComplete = true;
    /**
     * @var string The enctype for the form
     */
    protected $encType = 'text/plain';
    /**
     * @var bool Flag indicating of validation should be disabled
     */
    protected $noValidate = false;
    /**
     * @var FormComponent[] The array of components for the form
     */
    protected $components = array();
    /**
     * @var mixed[] The values for components in the form
     */
    protected $data = array();
    /**
     * Default constructor for {@link FormBuilder}
     * @param string[] $properties The properties to apply to the form
     * @throws FormBuilderConfigurationException
     */
    public function __construct($properties = array())
    {
        if (!is_array($properties)) {
            throw new FormBuilderConfigurationException('Properties of Form must be an array');
        }
        foreach ($properties as $key => $value) {
            $setterFunction = 'set'.ucfirst($key);
            if (!method_exists($this, $setterFunction)) {
                throw new FormBuilderConfigurationException('Setter function does not exist for ' . $setterFunction);
            }
            $this->$setterFunction($value);
        }
    }
    /**
     * Returns the type of the form element - in this case it will always be "form"
     * @return string The type of form element
     */
    public function getType()
    {
        return 'form';
    }
    /**
     * Sets the method the form should use
     * @param string $value The method to use
     * @return FormBuilder Returns itself
     */
    public function setMethod($value)
    {
        $value = strtoupper($value);
        if (!in_array($value, $this->allowedMethods)) {
            throw new FormBuilderConfigurationException(
                sprintf('Invalid method %s - allowed methods are: %s', $value, implode(', ', $this->allowedMethods))
            );
        }
        $this->method = $value;
        return $this;
    }
    /**
     * Sets the action the form should use
     * @param string $value The action to use
     * @return FormBuilder Returns itself
     */
    public function setAction($value)
    {
        if (!is_string($value)) {
            throw new FormBuilderConfigurationException(sprintf('Invalid action provided: %s', $value));
        }
        $this->action = $value;
        return $this;
    }
    /**
     * Specifies if teh form should allow autocompletion of fields
     * @param bool $autocomplete Flag indicating of the form allows autocomplete
     * @return FormBuilder Returns itself
     */
    public function setAutoComplete($autocomplete)
    {
        $this->autoComplete = (bool) $autocomplete;
        return $this;
    }
    /**
     * Sets the enctype the form should use
     * @param string $enctype The enctype to use
     * @return FormBuilder Returns itself
     */
    public function setEncType($enctype)
    {
        $enctype = strtolower($enctype);
        if (!in_array($enctype, $this->allowedEncTypes)) {
            throw new FormBuilderConfigurationException(sprintf('%s is not a valid enctype', $enctype));
        }
        $this->encType = $enctype;
        return $this;
    }
    /**
     * Specifies whether the form should use valdiation
     * @param bool $value Flag indicating of validation is used
     * @return FormBuilder Returns itself
     */
    public function setNoValidate($value)
    {
        if (!is_bool($value)) {
            throw new FormBuilderConfigurationException('novalidate MUST be boolean true or false');
        }
        $this->noValidate = (bool) $value;
        return $this;
    }
    /**
     * Returns the method for the form
     * @return string The form's method
     */
    public function getMethod()
    {
        return $this->method;
    }
    /**
     * Returns the action for the form
     * @return string The form's action
     */
    public function getAction()
    {
        return $this->action;
    }
    /**
     * Returns the value of the flag indicating of autocomplete is enabled
     * @return bool Flag indicating of the form should allow autocomplete
     */
    public function getAutoComplete()
    {
        return $this->autoComplete;
    }
    /**
     * Returns the enctype the form will use
     * @return string The enctype for the form
     */
    public function getEncType()
    {
        return $this->encType;
    }
    /**
     * Returns the value of the flag indicating of validation is disabled
     * @return bool Flag indicating if form should use novalidate
     */
    public function getNoValidate()
    {
        return $this->noValidate;
    }
    /**
     * Returns the array of components for the form
     * @return FormComponent[] The array of components
     */
    public function getComponents()
    {
        return $this->components;
    }
    /**
     * Adds a new component to the form
     * @param FormComponent $component The component to add
     * @return FormBuilder The instance of itself
     */
    public function addComponent(FormComponent $component)
    {
        if (!empty($this->components) && end($this->components)->getType() === 'fieldset') {
            end($this->components)->addFieldsetComponent($component);
        } else {
            $this->components[] = $component;
        }
        return $this;
    }
}
