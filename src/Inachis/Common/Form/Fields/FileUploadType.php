<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
use Inachis\Component\Common\Exception\FormBuilderConfigurationException;
/**
 * Object for handling `type="file"` fields
 */
class FileUploadType extends FormComponent
{
    /**
     *c@var string[] Array of allowed mime-types for the uploader
     */
    protected $accept = array();
    /**
     * @var bool Flag indicating if the field should automatically take focus
     *           on page load
     */
    protected $autoFocus = false;
    /**
     * @var bool Flag indicating if multiple files are allowed by the upload
     */
    protected $multiple = false;
    /**
     * Returns the current type for the form element
     * @return string The current element type
     */
    public function getType()
    {
        return 'file';
    }
    /**
     * Sets the value of {@link accept}
     * @param string[] $value The array of mime-types to allow
     * @return FileUploadType Reference to itself
     * @throws FormBuilderConfigurationException
     */
    public function setAccept($value)
    {
        if (!is_array($value)) {
            throw new FormBuilderConfigurationException('Directly set accept values should be an array');
        }
        $this->accept = $value;
        return $this;
    }
    /**
     * Sets the value of {@link autoFocus}
     * @param bool $value The value to set the property to
     * @return FileUploadType Returns a reference to itself
     */
    public function setAutoFocus($value)
    {
        $this->autoFocus = (bool) $value;
        return $this;
    }
    /**
     * Sets the value of {@link multiple}
     * @param string $value The value to set the property to
     * @return FileUploadType Returns a reference to itself
     */
    public function setMultiple($value)
    {
        $this->multiple = (bool) $value;
        return $this;
    }
    /**
     * Returns the array of accepted mime-types
     * @return string[] The array of accepted mime-types
     */
    public function getAccept()
    {
        return $this->accept;
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
     * Returns the value of {@link multiple}
     * @return bool $value The properties value
     */
    public function getMultiple()
    {
        return $this->multiple;
    }
    /**
     * Adds an allowed mime-type to {@link accept}
     * @param string $value The allowed mime-type
     * @return FileUploadType Returns a reference to itself
     */
    public function addAccept($value)
    {
        $this->accept[] = $value;
        return $this;
    }
}
