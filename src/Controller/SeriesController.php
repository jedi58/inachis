<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SeriesController extends AbstractInachisController
{
    /**
     * @Route("/incc/series/list", methods={"GET", "POST"})
     */
    public function list()
    {
        return $this->render('inadmin/series__list.html.twig', $this->data);
    }
    /**
     * @Route("/incc/series/edit", methods={"GET", "POST"})
     */
    public function edit()
    {
        return $this->render('inadmin/series__edit.html.twig', $this->data);
    }
}
