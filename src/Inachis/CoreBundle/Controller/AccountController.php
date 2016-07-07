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

class AccountController extends AbstractController
{
    /**
     * @Route("/setup")
     * @Method({"GET", "POST"})
     */
    public static function getSetup($request, $response, $service, $app)
    {
        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/dashboard')->send();
        }
        if (Application::getInstance()->getService('auth')->getUserManager()->getAllCount() > 0) {
            $response->redirect('/inadmin/signin')->send();
        }
        if ($request->method('post') && !empty($request->paramsPost()->get('username')) && !empty($request->paramsPost()->get('password'))) {
            // @todo add code for saving site title and URL
            if (Application::getInstance()->getService('auth')->create(
                $request->paramsPost()->get('username'),
                $request->paramsPost()->get('password'),
                array(
                    'displayName' => $request->paramsPost()->get('name'),
                    'email' => $request->paramsPost()->get('email')
                )
            )) {
                $response->redirect('/inadmin/signin')->send();
            }
        }
        $data = array(
            'form' => (new FormBuilder(array(
                'action' => '/setup',
                'autocomplete' => false,
                'cssClasses' => 'form form__login form__setup'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Setup your web application'
            )))
            ->addComponent(new TextType(array(
                'name' => 'siteName',
                'cssClasses' => 'field__wide',
                'label' => 'Site name',
                'placeholder' => 'e.g. My awesome site',
                'required' => true
            )))
            ->addComponent(new TextType(array(
                'name' => 'siteUrl',
                'cssClasses' => 'field__wide',
                'label' => 'URL',
                'required' => true,
                'type' => 'url',
                'value' => 'http' . ($request->isSecure() ? 's' : '') . '://' .
                    $request->server()->get('HTTP_HOST') .
                    str_replace('/setup', '', $request->server()->get('REQUEST_URI'))
            )))

            ->addComponent(new FieldsetType(array(
                'legend' => 'Administrator'
            )))
            ->addComponent(new TextType(array(
                'name' => 'username',
                'label' => 'Username',
                'required' => true,
                'value' => 'admin'
            )))
            ->addComponent(new TextType(array(
                'name' => 'password',
                'label' => 'Password',
                'required' => true,
                'type' => 'password'
            )))
            ->addComponent(new TextType(array(
                'name' => 'name',
                'label' => 'Name',
                'placeholder' => 'e.g. John Smith',
                'required' => true
            )))
            ->addComponent(new TextType(array(
                'name' => 'email',
                'label' => 'Email Address',
                'placeholder' => 'e.g. somebody@example.com',
                'required' => true,
                'type' => 'email'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Actions'
            )))
            ->addComponent(new ButtonType(array(
                'type' => 'submit',
                'cssClasses' => 'button button--positive',
                'label' => 'Continue…'
            ))),
            'errors' => self::$errors
        );
        $response->body($app->twig->render('setup__stage-1.html.twig', $data));
    }
    /**
     * @Route("/inadmin/signin")
     * @Method({"GET", "POST"})
     */
    public static function getSignin($request, $response, $service, $app)
    {
        // @todo get form structure from somewhere else
        // pass CSRF token into form
        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            if (Application::getInstance()->getService('session')->get('user')->hasCredentialsExpired()) {
                $response->redirect('/inadmin/change-password')->send();
            }
            $response->redirect('/inadmin/dashboard')->send();
        }
        if (Application::getInstance()->getService('auth')->getUserManager()->getAllCount() === 0) {
            $response->redirect('/setup')->send();
        }
        if ($request->method('post') && !empty($request->paramsPost()->get('loginUsername'))
                && !empty($request->paramsPost()->get('loginPassword'))) {
            if (Application::getInstance()->getService('auth')->login(
                $request->paramsPost()->get('loginUsername'),
                $request->paramsPost()->get('loginPassword')
            )) {
                $response->redirect('/inadmin/dashboard')->send();
            } else {
                self::$errors['username'] = 'Authentication Failed.';
            }
        }
        $data = array(
            'form' => (new FormBuilder(array(
                'action' => '/inadmin/signin',
                'autocomplete' => false,
                'cssClasses' => 'form form__login'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Sign in using email address'
            )))
            ->addComponent(new TextType(array(
                'name' => 'loginUsername',
                'label' => 'Username',
                'id' => 'form-login__username',
                'labelId' => 'form-login__username-label',
                'cssClasses' => 'text',
                'placeholder' => 'Username',
                'ariaAttributes' => array(
                    'required' => true,
                    'labelledby' => 'form-login__username-label'
                )
            )))
            ->addComponent(new TextType(array(
                'name' => 'loginPassword',
                'type' => 'password',
                'label' => 'Password',
                'id' => 'form-login__password',
                'labelId' => 'form-login__password-label',
                'cssClasses' => 'text',
                'placeholder' => 'Password',
                'ariaAttributes' => array(
                    'required' => true,
                    'labelledby' => 'form-login__password-label'
                )
            )))
            ->addComponent(new ChoiceType(array(
                'name' => 'rememberMe',
                'label' => 'Keep me logged in',
                'cssClasses' => 'checkbox',
                'value' => '1'
            )))
            ->addComponent(new ButtonType(array(
                'type' => 'submit',
                'cssClasses' => 'button button--positive',
                'label' => 'Login'
            ))),
            'data' => array(
                'loginUsername' => $request->paramsPost()->get('loginUsername'),
                'rememberMe' => !empty($request->paramsPost()->get('rememberMe')) ?
                    $request->paramsPost()->get('rememberMe') :
                    $request->cookies()->get('rememberMe')
            ),
            'errors' => self::$errors
        );
        $response->body($app->twig->render('admin__signin.html.twig', $data));
    }
    /**
     * @Route("/inadmin/signout")
     * @Method({"POST"})
     */
    public static function getSignout($request, $response, $service, $app)
    {
        Application::getInstance()->getService('auth')->logout();
        $response->redirect('/inadmin/signin')->send();
    }
    /**
     * @Route("/inadmin/forgot-password")
     * @Method({"GET"})
     */
    public static function getForgotPassword($request, $response, $service, $app)
    {
        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/dashboard')->send();
        }
        $data = array(
            'form' => (new FormBuilder(array(
                'action' => '/inadmin/forgot-password',
                'autocomplete' => false,
                'cssClasses' => 'form form__login form__forgot'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Enter your Email address / Username'
            )))
            ->addComponent(new TextType(array(
                'name' => 'forgot_email',
                'cssClasses' => 'text',
                'label' => 'Enter your email address to reset login',
                'placeholder' => 'user@example.com',
                'ariaAttributes' => array( 'required' => true ),
            )))
            //->addComponent(new ReCaptchaType(array()))
            ->addComponent(new ButtonType(array(
                'type' => 'submit',
                'cssClasses' => 'button button--positive',
                'label' => 'Reset password'
            )))
            ->addComponent(new ButtonType(array(
                'type' => 'button',
                'cssClasses' => 'button button--negative',
                'label' => 'Cancel'
            ))),
            'data' => array(
                "resetEmailAddress" => $request->paramsPost()->get('resetEmailAddress')
            ),
            'error' =>array(
                //validate email address format
            )
        );
        $response->body($app->twig->render('admin__forgot-password.html.twig', $data));
    }
    /**
     * @Route("/inadmin/forgot-password")
     * @Method({"POST"})
     */
    public static function getForgotPasswordSent($request, $response, $service, $app)
    {
        if (Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/dashboard')->send();
        }
        if (false) { // @todo if request contains errors then use
            return self::getForgotPassword($request, $response, $service, $app);
        }
        $response->body($app->twig->render('admin__forgot-password-sent.html.twig', array()));
    }
    /**
     * @Route("/inadmin/user-management")
     * @Method({"GET", "POST"})
     */
    public static function getAdminList($request, $response, $service, $app)
    {
        if (!Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/signin')->send();
        }
        $response->body('Show all admins');
    }
    /**
     * @Route("/inadmin/user/{id}")
     * @Method({"GET", "POST"})
     */
    public static function getAdminDetails($request, $response, $service, $app)
    {
        if (!Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/signin')->send();
        }
        $response->body('Show details of specific admin');
    }
    /**
     * @Route("/inadmin/dashboard")
     * @Method({"GET"})
     */
    public static function getAdminDashboard($request, $response, $service, $app)
    {
        if (!Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/signin')->send();
        }
        $data = array(
            'session' => $_SESSION
        );
        $response->body($app->twig->render('admin__dashboard.html.twig', $data));
    }
    /**
     * @Route("/inadmin/settings")
     * @Method({"GET", "POST"})
     */
    public static function getAdminSettingsMain($request, $response, $service, $app)
    {
        if (!Application::getInstance()->requireAuthenticationService()->isAuthenticated()) {
            $response->redirect('/inadmin/signin')->send();
        }
        $response->body('Show settings page for signed in admin');
    }
}
