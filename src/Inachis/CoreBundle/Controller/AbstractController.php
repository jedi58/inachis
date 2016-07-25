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
 * Abstract class used by all controller
 */
abstract class AbstractController
{
	/**
	 * @var mixed[] Variables to be accessible to Twig templates
	 */
	protected static $data = array();
    /**
     * @var string[] Array of any error messages for the current view
     */
    protected static $errors = array();
    /**
     * @var FormBuilder A FormBuilder object for use in contructing forms for the current view
     */
    protected static $formBuilder;
    /**
     * Default constructor initialises the {@link FormBuilder}
     */
    public function __construct()
    {
        self::$formBuilder = new FormBuilder();
        self::$data['session'] = $_SESSION;
    }
    /**
     * Returns all current errors on the page
     * @return string[] The array of errors
     */
    public static function getErrors()
    {
        return self::$errors;
    }
    /**
     * Returns a specific error message given by it's unique name
     * @return string The requested error message if set
     */
    public static function getError($error)
    {
        return isset(self::$errors[$error]) ? self::$errors[$error] : null;
    }
    /**
     * Adds an error to the current controller to be displayed/handled on
     * by the view
     * @param string $error Unique identifier for the error
     * @param string $message The friendly message for the error
     */
    public static function addError($error, $message)
    {
        self::$errors[$error] = (string) $message;
    }
}
