<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
/**
 * Object for handling select fields
 */
class SelectOptionGroupType extends AbstractSelectType
{
    /**
     * @var string The `innerHTML` for the option
     */
    protected $label;
    /**
     * Default constructor for {@link SelectOptionGroupType}
     * @param string $label The name of the group
     * @param string[] $options The {@link SelectOptionType} elements for the group
     */
    public function __construct($label = '', $options = array())
    {
        $this->setLabel($label);
        $this->addArrayAsOptions($options);
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
     * @return SelectOptionGroupType Returns the current object
     */
    public function setLabel($label)
    {
        $this->label = $label;
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
     * Converts the label for the option group into a suitable HTML-valid ID
     * @return string The converted ID
     */
    public function getLabelAsId()
    {
        return preg_replace('/[^a-zA-Z0-9\-\_]/','', lcfirst(ucwords(strtolower($this->label))));
    }
}
