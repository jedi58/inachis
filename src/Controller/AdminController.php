<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractInachisController
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
        $this->data['page']['title'] = 'Profile';

        return $this->render('inadmin/profile.html.twig', $this->data);
    }
}
