<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use App\Entity\User;
use App\Form\SetupStage1Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SetupController extends AbstractInachisController
{
    /**
     * @Route("/setup", methods={"GET", "POST"})
     * @param Request $request
     * @return Response
     */
    public function stage1(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        // @todo remove false from if statement
        if (false && $entityManager->getRepository(User::class)->getAllCount() > 0) {
            return $this->redirectToRoute(
                'app_dashboard_default',
                [],
                Response::HTTP_PERMANENTLY_REDIRECT
            );
        }
        $form = $this->createForm(SetupStage1Type::class, [
            'defaultUrl' => 'https://' . $request->getHttpHost(),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }
//        if ($request->method('post') && !empty($request->paramsPost()->get('username')) && !empty($request->paramsPost()->get('password'))) {
//            if (Application::getInstance()->getService('auth')->create(
//                $request->paramsPost()->get('username'),
//                $request->paramsPost()->get('password'),
//                array(
//                    'displayName' => $request->paramsPost()->get('name'),
//                    'email' => $request->paramsPost()->get('email')
//                )
//            )) {
//                return $response->redirect('/inadmin/signin')->send();
//            }
//        }

        $this->data['page']['title'] = 'Inachis Install - Step 1';
        $this->data['form'] = $form->createView();
        return $this->render('setup/stage-1.html.twig', $this->data);
    }
}
