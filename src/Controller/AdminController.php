<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/incc/user-management", methods={"GET", "POST"})
     */
    public function adminList()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return new Response('Show all admins');
    }

    /**
     * @Route("/incc/user/{id}", methods={"GET", "POST"})
     */
    public function adminDetails($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//        self::$data['page'] = array('title' => 'Profile');
        return $this->render('inadmin/profile.html.twig', self::$data);
    }

    /**
     * @Route("/incc/settings", methods={"GET", "POST"})
     */
    public function adminSettingsMain()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return new Response('Show settings page for signed in admin');
    }
}
