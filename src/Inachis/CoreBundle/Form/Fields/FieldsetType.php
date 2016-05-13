<?php

namespace Inachis\Component\CoreBundle\Form\Fields;

use Inachis\Component\CoreBundle\Form\FormComponent;
use Inachis\Component\CoreBundle\Exception\FormBuilderConfigurationException;

/**
 * Object for handling select fields
 */
class FieldsetType extends FormComponent
{
    /**
     * @var string The legend for the fieldset
     */
    protected $legend;
    /**
     * @var FormComponent[] The array of components for the fieldset
     */
    protected $fieldsetComponents;
    /**
     * Default constructor for {@link SelectOptionGroupType}
     * @param string $label The name of the group
     * @param string[] $options The {@link SelectOptionType} elements for the group
     */
    public function __construct($properties = array())
    {
        if (!is_array($properties)) {
            throw new FormBuilderConfigurationException('Properties of fieldset must be an array');
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
     * Returns the type of field
     * @return string The field type
     */
    public function getType()
    {
        return 'fieldset';
    }
    /**
     * Sets the value of {@link label}
     * @param string $label The value to set the property to
     * @return SelectOptionGroupType Returns the current object
     */
    public function setLegend($legend)
    {
        $this->legend = $legend;
        return $this;
    }
    /**
     * Returns the value of {@link label}
     * @return bool The properties value
     */
    public function getLegend()
    {
        return $this->legend;
    }
    /**
     * Returns the array of components in the current fieldset
     * @return FormComponent[] The array of form components for the fieldset
     */
    public function getFieldsetComponents()
    {
        return $this->fieldsetComponents;
    }
    /**
     * Adds a new component to the form
     * @param FormComponent $component The component to add
     * @return FormBuilder The instance of itself
     */
    public function addFieldsetComponent(FormComponent $component)
    {
        // add line here for checking if this needs to be in a fieldset
        $this->fieldsetComponents[] = $component;
        return $this;
    }
}
