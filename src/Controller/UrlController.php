<?php

namespace App\Controller;

use App\Entity\Url;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UrlController extends AbstractInachisController
{
    /**
     * @Route("/incc/url/list", methods={"GET", "POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function list(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        // @todo add code for handling deleting of URLs and setting as default
//        if ($form->isSubmitted()) && $form->isValid()) {
//            if ($form->get('delete')->isClicked()) {
//
//            }
//        }
        $offset = 0;
        $this->data['urls'] = $entityManager->getRepository(Url::class)->getAll(
            $offset,
            10,
            [],
            [
                ['q.content', 'desc'],
                ['q.default', 'desc'],
                ['q.link', 'asc'],
            ]
        );
        $this->data['page']['offset'] = $offset;
        $this->data['page']['limit'] = 20;
        $this->data['page']['title'] = 'URLs';

        return $this->render('inadmin/url__list.html.twig', $this->data);
    }
}
