<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Application;
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
    /**
     * TUsed when the request requires authentication; if the not authenticated
     * then the user's requested page URL is stored in the session and then
     * redirected to the sign-in page. Otherwise, it also tests if their password
     * has expired
     * @param Request $request The request object from the router
     * @param Response $response The response object from the router
     */
    public static function redirectIfNotAuthenticated($request, $response)
    {
        if (!Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $referer = parse_url($request->server()->get('HTTP_REFERER'));
            if (!empty($referer) && (empty($referer['host']) || $referer['host'] == $request->server()->get('HTTP_HOST'))) {
                Application::getInstance()->requireSessionService()->set('referer', $request->server()->get('HTTP_REFERER'));
            }
            $response->redirect('/inadmin/signin')->send();
        }
        self::redirectIfPasswordExpired($request, $response);
    }
    /**
     * If the user is trying to access a page such as sign-in but is already authenticated
     * they will be redirected to the dashboard
     * @param Response $response The response object from the router
     */
    public static function redirectIfAuthenticated($response)
    {
        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/')->send();
        }
    }
    /**
     * If the user's password has expired their current request URL will be stored in the session
     * and will then be redirected to change-password
     * @param Request $request The request object from the router
     * @param Response $response The response object from the router
     */
    public static function redirectIfPasswordExpired($request, $response)
    {
        if (Application::getInstance()->getService('session')->get('user')->hasCredentialsExpired()) {
            $referer = parse_url($request->server()->get('HTTP_REFERER'));
            if (!empty($referer) &&
                (empty($referer['host']) || $referer['host'] == $request->server()->get('HTTP_HOST')) &&
                strpos($referer, 'change-password') === false
            ) {
                Application::getInstance()->requireSessionService()->set('referer', $request->server()->get('HTTP_REFERER'));
            }
            $response->redirect('/inadmin/change-password')->send();
        }
    }
    /**
     * If the user has a referer set they will be redirected to it otherwise they will be redirected to
     * the dashboard
     * @param Response $response The response object from the router
     */
    public static function redirectToRefererOrDashboard($response)
    {
        $referer = Application::getInstance()->requireSessionService()->get('referer');
        if (!empty($referer)) {
            $response->redirect($referer)->send();
        }
        $response->redirect('/inadmin/')->send();
    }
}
