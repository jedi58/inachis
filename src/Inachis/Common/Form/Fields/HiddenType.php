<?php

namespace Inachis\Component\Common\Form\Fields;

use Inachis\Component\Common\Form\FormComponent;
/**
 * Object for handling `type="hidden"` buttons
 */
class HiddenType extends FormComponent
{
    /**
     * @var string Use no wrapper element for hidden elements by default
     */
    protected $wrapperElement = '';
    /**
     * Do not disable autocomplete on hidden fields by default
     */
    protected $autoComplete = true;
    /**
     * Returns the type of the current form component
     * @return string The type of the form component
     */
    public function getType()
    {
        return 'hidden';
    }
}
