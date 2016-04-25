<?php

namespace Inachis\Component\CoreBundle\Controller;

class AccountController
{
    public static function getSignin($request, $response, $service, $app)
    {
        $response->body('Signin page');
    }
    public static function getSignout($request, $response, $service, $app)
    {
        $response->body('Show signed out message');
    }
    public static function getForgotPassword($request, $response, $service, $app)
    {
        $response->body('Show request for forgotten password reset');
    }
    public static function getForgotPasswordSent($request, $response, $service, $app)
    {
        $response->body('Show confirmation for forgotten password');
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
