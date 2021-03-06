<?php

namespace App\EventListener;

use App\Security\ContentSecurityPolicy;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class AdminResponseEvent implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * AdminResponseEvent constructor.
     *
     * @param LoggerInterface    $logger
     * @param ContainerInterface $container
     */
    public function __construct(LoggerInterface $logger, ContainerInterface $container)
    {
        $this->logger = $logger;
        $this->container = $container;
    }

    /**
     * @param ResponseEvent $event
     */
    public function onKernelRequest(RequestEvent $event)
    {
        // Redirect to /setup if no admins
        // Redirect if not signed in
        // Redirect if password has expired
        //$request = $event->getRequest();
    }

    /**
     * @param ResponseEvent $event
     */
    public function onKernelResponse(ResponseEvent $event)
    {
        $this->sendSecurityHeaders($event);
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();
        if (method_exists($controller[0], 'setDefaults')) {
            $controller[0]->setDefaults();
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST    => 'onKernelRequest',
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE   => 'onKernelResponse',
        ];
    }

    /**
     * @param ResponseEvent $event
     */
    public function sendSecurityHeaders(ResponseEvent $event)
    {
        $event->getResponse()->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $event->getResponse()->headers->set('X-XSS-Protection', '1; mode=block');
        $event->getResponse()->headers->set('X-Content-Type-Options', 'nosniff');
        $cspHeader = ContentSecurityPolicy::getInstance()->getCSPEnforceHeader(
            $this->container->getParameter('csp')
        );
        if (!empty($cspHeader)) {
            $event->getResponse()->headers->set('Content-Security-Policy', $cspHeader);
        }
        $cspReportHeader = ContentSecurityPolicy::getInstance()->getCSPReportHeader(
            $this->container->getParameter('csp')
        );
        if (!empty($cspReportHeader)) {
            $event->getResponse()->headers->set('Content-Security-Policy-Report-Only', $cspReportHeader);
        }
    }
}
