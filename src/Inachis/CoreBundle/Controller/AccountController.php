<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Form\FormBuilder;
use Inachis\Component\CoreBundle\Form\Fields\ButtonType;
use Inachis\Component\CoreBundle\Form\Fields\ChoiceType;
use Inachis\Component\CoreBundle\Form\Fields\FileUploadType;
use Inachis\Component\CoreBundle\Form\Fields\HiddenType;
use Inachis\Component\CoreBundle\Form\Fields\NumberType;
use Inachis\Component\CoreBundle\Form\Fields\SelectType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionGroupType;
use Inachis\Component\CoreBundle\Form\Fields\TextType;
use Inachis\Component\CoreBundle\Form\Fields\TextAreaType;

class AccountController
{
    public static function getSignin($request, $response, $service, $app)
    {
        $data = array(
            'data' => array(
                'loginUsername' => $request->paramsPost()->get('loginUsername'),
                'rememberMe' => $request->cookies()->get('rememberMe')
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
