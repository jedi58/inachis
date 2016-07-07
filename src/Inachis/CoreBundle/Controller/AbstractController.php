<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Form\FormBuilder;
use Inachis\Component\CoreBundle\Form\Fields\ButtonType;
use Inachis\Component\CoreBundle\Form\Fields\ChoiceType;
use Inachis\Component\CoreBundle\Form\Fields\FieldsetType;
use Inachis\Component\CoreBundle\Form\Fields\FileUploadType;
use Inachis\Component\CoreBundle\Form\Fields\HiddenType;
use Inachis\Component\CoreBundle\Form\Fields\NumberType;
//use Inachis\Component\CoreBundle\Form\Fields\ReCaptchaType;
use Inachis\Component\CoreBundle\Form\Fields\SelectType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionGroupType;
//use Inachis\Component\CoreBundle\Form\Fields\TableType;
use Inachis\Component\CoreBundle\Form\Fields\TextType;
use Inachis\Component\CoreBundle\Form\Fields\TextAreaType;

/**
 *
 */
abstract class AbstractController
{
    /**
     *
     */
    protected static $errors = array();
    /**
     *
     */
    protected static $formBuilder;
    /**
     *
     */
    public function __construct()
    {
        $this->formBuilder = new FormBuilder();
    }
    /**
     *
     */
    public static function getErrors()
    {
        return $this->errors;
    }
    /**
     *
     */
    public static function addError($error, $message)
    {
        $this->errors[$error] = (string) $message;
    }
}
