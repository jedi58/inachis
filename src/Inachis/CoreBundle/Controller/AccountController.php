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

class AccountController
{
    public static function getSignin($request, $response, $service, $app)
    {
        // @todo get form structure from somewhere else
        // add authenticated user check
        // pass CSRF token into form
        //Application::getInstance()->getService('session'))->hasKey()

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
                'cssClasses' => 'button button--info',
                'label' => 'Login'
            ))),
            'data' => array(
                'loginUsername' => $request->paramsPost()->get('loginUsername'),
                'rememberMe' => !empty($request->paramsPost()->get('rememberMe')) ?
                    $request->paramsPost()->get('rememberMe') :
                    $request->cookies()->get('rememberMe')
            )
        );
        $response->body($app->twig->render('admin__signin.html.twig', $data));
    }
    public static function getSignout($request, $response, $service, $app)
    {
        // need to perform actual signout
        $response->body($app->twig->render('admin__signed-out.html.twig'));
    }
    public static function getForgotPassword($request, $response, $service, $app)
    {
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
                'cssClasses' => 'button button--info',
                'label' => 'Reset password'
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
    public static function getForgotPasswordSent($request, $response, $service, $app)
    {
        if (false) {
            // if request contains errors then use self::getForgotPassword($request, $response, $service, $app) instead
        }
        $response->body($app->twig->render('admin__forgot-password-sent.html.twig', array()));
    }
    public static function getAdminList($request, $response, $service, $app)
    {
        $response->body('Show all admins');
    }
    public static function getAdminDetails($request, $response, $service, $app)
    {
        $response->body('Show details of specific admin');
    }
    public static function getAdminDashboard($request, $response, $service, $app)
    {
        $response->body('Show dashboard for signed in admin');
    }
    public static function getAdminSettingsMain($request, $response, $service, $app)
    {
        $response->body('Show settings page for signed in admin');
    }
}
