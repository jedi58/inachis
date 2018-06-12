<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImportController extends Controller
{
    /**
     * @Route("/incc/import", methods={"GET"})
     */
    public function index()
    {
        // @todo show dragdrop interface for selecting file for import
        return $this->render('import/index.html.twig', [
            'controller_name' => 'ImportController',
        ]);
    }

    /**
     * @Route("/incc/import", methods={"PUT"})
     */
    public function process()
    {
        // @todo carry out import of .md and .zip
    }
}
