<?php

namespace Inachis\Component\CoreBundle\Form;

use Inachis\Component\CoreBundle\Exception\FormBuilderConfigurationException;

/**
 * Abstract class that provides attributes and functions that are common to both
 * forms and form components
 */
abstract class AbstractFormType
{
    /**
     * @var string The id of the element
     */
    protected $id;
    /**
     * @var string The name of the element
     */
    protected $name;
    /**
     * @var string[] The array of classes to apply to the object
     */
    protected $cssClasses = array();
    /**
     * @var string[] Array of attributes for the element; e.g. 'item' => 'test'
     *               would be output as `data-item="test"` by the view
     */
    protected $dataAttributes = array();
    /**
     * Returns the type of the current element
     * @return string The type of the current element
     */
    abstract public function getType();
    /**
     * Sets the ID for the current form element
     * @var string $value The new ID for the form
     * @return AbstractFormType A reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setId($value)
    {
        if (!empty($value) && !preg_match('/^[a-zA-Z0-9\-\_]+$/', $value)) {
            throw new FormBuilderConfigurationException(sprintf('Invalid id attribute \'%s\'.', $value));
        }
        $this->id = $value;
        return $this;
    }
    /**
     * Returns the ID of the current form element
     * @return string The ID of the form element
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Sets the name for the current form element
     * @var string $value The new name for the form
     * @return AbstractFormType A reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setName($value)
    {
        if (!$this->isValidName($value)) {
            throw new FormBuilderConfigurationException(sprintf('Invalid name attribute \'%s\'.', $value));
        }
        $this->name = $value;
        return $this;
    }
    /**
     * Returns the name of the current form element
     * @return string The name of the form element
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Sets the array of CSS classes to apply to the element
     * @var string[] $value The CSS to apply to the element
     * @return AbstractFormType A reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setCssClasses($value)
    {
        if (!is_array($value) && !empty($value)) {
            return $this->setCssClassesFromString($value);
        }
        $this->cssClasses = $value;
        return $this;
    }
    public function setCssClassesFromString($value)
    {
        $this->cssClasses = explode(' ', $value);
        return $this;
    }
    /**
     * Returns the array of CSS classes for the form element
     * @return string[] The array of CSS classes for the element
     */
    public function getCssClasses()
    {
        return $this->cssClasses;
    }
    /**
     * Returns the array of CSS classes as a string so it can be used
     * directly in a class attribute
     * @return string The CSS classes to use
     */
    public function getCssClassesAsString()
    {
        return implode(' ', $this->cssClasses);
    }
    /**
     * Adds a CSS classname to the form element
     * @var string $value The CSS class to add
     * @return AbstractFormType A reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function addCssClass($value)
    {
        if (!$this->isValidName($value)) {
            throw new FormBuilderConfigurationException(sprintf('Class name \'%s\' is invalid', $value));
        }
        $this->cssClasses[] = $value;
        return $this;
    }
    /**
     * Removes a CSS classname to the form element
     * @var string $value The CSS class to remove
     * @return AbstractFormType A reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function removeCssClass($value)
    {
        if (!isset($this->cssClasses[$value])) {
            throw new FormBuilderConfigurationException(
                sprintf('Class \'%s\' does not exist - could not be removed', $value)
            );
        }
        unset($this->cssClasses[$value]);
        return $this;
    }
    /**
     * Validates the name for the form to confirm it's valid in HTML
     * @param string $value The HTML name to test validity of
     * @return bool The result of testing if the name is valid in HTML
     */
    public function isValidName($value)
    {
        return empty($value) || preg_match('/^[a-zA-Z0-9_\-]+$/', $value);
    }
}
