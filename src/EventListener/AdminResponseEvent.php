<?php

namespace App\EventSubscriber;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

final class AdminResponseEvent
{
    protected $headers;

    public function __construct()
    {
        $this->headers = $event->getResponse()->headers;
    }
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->sendSecurityHeaders();
    }
    public function sendSecurityHeaders()
    {
        $this->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $this->headers->set('X-XSS-Protection', '1; mode=block');
        $this->headers->set('X-Content-Type-Options', 'nosniff');
//            $cspHeader = ContentSecurityPolicy::getInstance()->getCSPEnforceHeader();
//            if (!empty($cspHeader)) {
//                $this->headers->set('Content-Security-Policy', $cspHeader);
//            };
//            $cspReportHeader = ContentSecurityPolicy::getInstance()->getCSPReportHeader();
//            if (!empty($cspReportHeader)) {
//                $this->headers->set('Content-Security-Policy-Report-Only', $cspReportHeader);
//            }
    }
    private function getStandardData(Request $request)
    {
        return [
//            'session' => $_SESSION,
//            'domain' => ($request->isSecure() ? 'https://' : 'http://') . $request->server()->get('HTTP_HOST'),
//            'siteTitle' => !empty(Application::getInstance()->getConfig()['system']->title) ?
//                Application::getInstance()->getConfig()['system']->title :
//                null,
//            'google' => Application::getInstance()->getConfig()['system']->google
            'settings' => [
                'siteTitle' => '',
                'domain' => '',
                'google' => [],
                'language' => 'en',
                'textDirection' => 'ltr',
                'abstract' => '',
                'fb_app_id' => ''
            ],
            'page' => [
                'self' => '',
                'title' => '',
                'description' => ''
            ],
            'post' => [
                'featureImage' => ''
            ],
            'session' => [
                'user' => []
            ],
        ];
//        self::sendSecurityHeaders($response);
    }
}