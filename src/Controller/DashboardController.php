<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class DashboardController extends AbstractInachisController
{
    /**
     * @Route("/incc", methods={"GET"})
     *
     * @param Request             $request    The request made to the controller
     * @param TranslatorInterface $translator
     *
     * @return Response
     */
    public function default(Request $request, TranslatorInterface $translator)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $this->data['page'] = [
            'tab'   => 'dashboard',
            'title' => 'Dashboard',
        ];
        $this->data['dashboard'] = [
            'draftCount'    => 0,
            'publishCount'  => 0,
            'upcomingCount' => 0,
            'drafts'        => $entityManager->getRepository(Page::class)->getAll(
                0,
                5,
                [
                    'q.status = :status',
                    [
                        'status' => Page::DRAFT,
                    ],
                ],
                'q.postDate ASC, q.modDate'
            ),
            'upcoming' => $entityManager->getRepository(Page::class)->getAll(
                0,
                5,
                [
                    'q.status = :status AND q.postDate > :postDate',
                    [
                        'status'   => Page::PUBLISHED,
                        'postDate' => new \DateTime(),
                    ],
                ],
                'q.postDate ASC, q.modDate'
            ),
            'posts' => $entityManager->getRepository(Page::class)->getAll(
                0,
                5,
                [
                    'q.status = :status AND q.postDate <= :postDate',
                    [
                        'status'   => Page::PUBLISHED,
                        'postDate' => new \DateTime(),
                    ],
                ],
                'q.postDate DESC, q.modDate'
            ),
        ];
        $this->data['dashboard']['draftCount'] = $this->data['dashboard']['drafts']->count();
        $this->data['dashboard']['upcomingCount'] = $this->data['dashboard']['upcoming']->count();
        $this->data['dashboard']['publishCount'] = $this->data['dashboard']['posts']->count();
        return $this->render('inadmin/dashboard.html.twig', $this->data);
    }
}
