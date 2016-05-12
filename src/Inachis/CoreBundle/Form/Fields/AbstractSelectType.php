<?php

namespace Inachis\Component\CoreBundle\Form\Fields;

use Inachis\Component\CoreBundle\Form\FormComponent;
use Inachis\Component\CoreBundle\Exception\FormBuilderConfigurationException;

/**
 * Object for handling select fields
 */
abstract class AbstractSelectType extends FormComponent
{
    /**
     * @var SelectOptionType|SelectOptionGroupType[] The options for the select
     */
    protected $options = array();
    /**
     * Sets {@link options} to a pre-determined array
     * @param SelectOptionType|SelectOptionGroupType[] The array of options
     * @return AbstractSelectType Returns a reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setOptions($value)
    {
        if (!is_array($value)) {
            throw new FormBuilderConfigurationException('Options must be an array when set directly');
        }
        $this->options = $value;
        return $this;
    }
    /**
     * Returns the array of options for the {@link SelectType}
     * @return AbstractSelectType[] The array for the {@link SelectType}
     */
    public function getOptions()
    {
        return $this->options;
    }
    /**
     * Adds a HTML option to the current select object
     * @param SelectOptionType $option The option to add
     * @return AbstractSelectType Returns the current object
     */
    public function addOption(SelectOptionType $option)
    {
        $this->options[] = $option;
        return $this;
    }
    /**
     * Adds a HTML optgroup to the current select object
     * @param SelectOptionGroupType $optionGroup The group of options to add
     * @return AbstractSelectType Returns the current object
     */
    public function addOptionGroup(SelectOptionGroupType $optionGroup)
    {
        $this->options['group_' . $optionGroup->getLabelAsId()] = $optionGroup;
        return $this;
    }
    /**
     * Converts an array of values into {@link SelectOptionType} objects. If an
     * associative array is provided it will use the array keys as the value attribute
     * for the option, otherwise values are used as both value and innerHTML
     * @param string[] $optionArray The areay of options to convert to {@link SelectOptionType}
     * @return AbstractSelectType The reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function addArrayAsOptions($optionArray)
    {
        if (!is_array($optionArray)) {
            throw new FormBuilderConfigurationException('Options must be an array');
        }
        foreach ($optionArray as $key=>$option) {
            $this->addOption(new SelectOptionType(
                $option,
                is_string($key) ? $key : $option
            ));
        }
        return $this;
    }
}
