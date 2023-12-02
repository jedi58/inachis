<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\SetupStage1Type;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SetupController extends AbstractInachisController
{
    /**
     * @Route("/setup", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function stage1(Request $request)
    {
        if ($this->entityManager->getRepository(User::class)->getAllCount() > 0) {
            return $this->redirectToRoute(
                'app_dashboard_default',
                [],
                Response::HTTP_PERMANENTLY_REDIRECT
            );
        }

        $this->data['page']['title'] = 'Inachis Install';
        return $this->render('setup/stage-1.html.twig', $this->data);
    }
}
