<?php

namespace Inachis\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/inadmin/user-management", methods={"GET", "POST"})
     */
    public function adminList()
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        if ($response->isLocked()) {
//            return null;
//        }
        return new Response('Show all admins');
    }

    /**
     * @Route("/inadmin/user/{id}", methods={"GET", "POST"})
     */
    public function adminDetails($id)
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        if ($response->isLocked()) {
//            return null;
//        }
//        self::adminInit($request, $response);
//        self::$data['page'] = array('title' => 'Profile');
        return $this->render('inadmin/profile.html.twig', self::$data);
    }

    /**
     * @Route("/inadmin/settings", methods={"GET", "POST"})
     */
    public function adminSettingsMain()
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        if ($response->isLocked()) {
//            return;
//        }
        return new Response('Show settings page for signed in admin');
    }
}
