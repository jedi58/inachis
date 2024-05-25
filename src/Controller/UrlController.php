<?php

namespace App\Controller;

use App\Entity\Url;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UrlController extends AbstractInachisController
{
    /**
     * @param Request $request
     * @return Response
     */
    #[Route(
        "/incc/url/list/{offset}/{limit}",
        methods: [ "GET", "POST" ],
        defaults: [ "offset" => 0, "limit" => 20 ],
        requirements: [ "offset" => "\d+", "limit" => "\d+" ]
    )]
    public function list(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        // @todo add code for handling deleting of URLs and setting as default
//        if ($form->isSubmitted()) && $form->isValid()) {
//            if ($form->get('delete')->isClicked()) {
//
//            }
//        }
        $filters = array_filter($request->get('filter', []));
        $offset = (int) $request->get('offset', 0);
        $limit = $this->entityManager->getRepository(Url::class)->getMaxItemsToShow();
        $this->data['dataset'] = $this->entityManager->getRepository(Url::class)->getAll(
            $offset,
            $limit,
            [],
            [
//                ['q.content', 'desc'],
                ['q.default', 'desc'],
                ['q.link', 'asc'],
            ]
        );
        $this->data['form'] = $form->createView();
        $this->data['filters'] = $filters;
        $this->data['page']['offset'] = $offset;
        $this->data['page']['limit'] = $limit;
        $this->data['page']['title'] = 'URLs';

        return $this->render('inadmin/url__list.html.twig', $this->data);
    }
}
