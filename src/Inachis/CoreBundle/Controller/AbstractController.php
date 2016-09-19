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
use Inachis\Component\CoreBundle\Security\ContentSecurityPolicy;
use Klein\Request;
use Klein\Response;

/**
 * Abstract class used by all controller
 */
abstract class AbstractController
{
    /**
     * $var AbstractController The instance of self
     */
    private static $instance;
    /**
     * @var mixed[] Variables to be accessible to Twig templates
     */
    protected static $data = array();
    /**
     * @var string[] Array of any error messages for the current view
     */
    protected static $errors = array();
    /**
     * @var FormBuilder A FormBuilder object for use in constructing forms for the current view
     */
    protected static $formBuilder;
    /**
     * Returns an instance of {@link AbstractController}
     * @return Application The current or a new instance of {@link Application}
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }
    /**
     * Default constructor initialises the {@link FormBuilder}
     * @param \Klein\Request $request The request being made
     * @param \Klein\Response $response The response to be sent
     */
    public static function adminInit($request, $response)
    {
        self::$formBuilder = new FormBuilder();
        self::$data['session'] = $_SESSION;
        self::$data['settings'] = array(
            'domain' => 'http://' . $request->server()->get('HTTP_HOST'),
            'title' => !empty(Application::getInstance()->getConfig()['system']->title) ?
                Application::getInstance()->getConfig()['system']->title :
                null,
            'google' => Application::getInstance()->getConfig()['system']->google
        );
        self::sendSecurityHeaders($response);
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
     * @param string $error The name of the error message to retrieve
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
     * Used when the request requires authentication; if the not authenticated
     * then the user's requested page URL is stored in the session and then
     * redirected to the sign-in page. Otherwise, it also tests if their password
     * has expired
     * @param Request $request The request object from the router
     * @param Response $response The response object from the router
     * @return mixed
     */
    public static function redirectIfNotAuthenticated($request, $response)
    {
        if ($response->isLocked()) {
            return null;
        }
        if (!Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $referrer = parse_url($request->server()->get('REQUEST_URI'));
            if (!empty($referrer) && (empty($referrer['host']) || $referrer['host'] == $request->server()->get('HTTP_HOST'))) {
                Application::getInstance()->requireSessionService()->set('referrer', $request->server()->get('REQUEST_URI'));
            }
            return $response->redirect('/inadmin/signin')->send();
        }
        return self::redirectIfPasswordExpired($request, $response);
    }
    /**
     * If the user is trying to access a page such as sign-in but is already authenticated
     * they will be redirected to the dashboard
     * @param Response $response The response object from the router
     */
    public static function redirectIfAuthenticated($response)
    {
        if ($response->isLocked()) {
            return;
        }
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
        if ($response->isLocked()) {
            return;
        }
        if (Application::getInstance()->getService('session')->get('user')->hasCredentialsExpired()) {
            $referrer = parse_url($request->server()->get('HTTP_REFERER'));
            if (!empty($referrer) &&
                (empty($referrer['host']) || $referrer['host'] == $request->server()->get('HTTP_HOST')) &&
                strpos($referrer, 'change-password') === false
            ) {
                Application::getInstance()->requireSessionService()->set('referrer', $request->server()->get('HTTP_REFERER'));
            }
            $response->redirect('/inadmin/change-password')->send();
        }
    }
    /**
     * If the user has a referrer set they will be redirected to it otherwise they will be redirected to
     * the dashboard
     * @param Response $response The response object from the router
     * @return mixed
     */
    public static function redirectToReferrerOrDashboard($response)
    {
        if ($response->isLocked()) {
            return null;
        }
        $referrer = Application::getInstance()->requireSessionService()->get('referrer');
        if (!empty($referrer)) {
            return $response->redirect($referrer)->send();
        }
        return $response->redirect('/inadmin/')->send();
    }
    /**
     * Sends security related headers with the response
     * @param Response $response The response object from the router
     */
    public static function sendSecurityHeaders($response)
    {
        if (!$response->isLocked()) {
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('X-Content-Type-Options', 'nosniff');
            $cspHeader = ContentSecurityPolicy::getInstance()->getCSPEnforceHeader();
            if (!empty($cspHeader)) {
                $response->header('Content-Security-Policy', $cspHeader);
            };
            $cspReportHeader = ContentSecurityPolicy::getInstance()->getCSPReportHeader();
            if (!empty($cspReportHeader)) {
                $response->header('Content-Security-Policy-Report-Only', $cspReportHeader);
            }
        }
    }
}
