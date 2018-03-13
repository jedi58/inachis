<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractInachisController
{
    /**
     * @Route(
     *     "/",
     *     methods={"GET"}
     * )
     */
    public function default()
    {
        return new Response('Homepage!');
    }
}
