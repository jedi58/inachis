<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractInachisController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function homepage()
    {
        return $this->render('web/homepage.html.twig', $this->data);
    }
}
