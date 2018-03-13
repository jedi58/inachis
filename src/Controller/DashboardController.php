<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class DashboardController extends AbstractInachisController
{
    /**
     * @Route("/incc", methods={"GET"})
     * @param Request $request The request made to the controller
     * @param TranslatorInterface $translator
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function default(Request $request, TranslatorInterface $translator)
    {
        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
//        $pageManager = new PageManager(Application::getInstance()->getService('em'));
//        self::adminInit($request, $response);

        $entityManager = $this->getDoctrine()->getManager();
dump($this->get('security.token_storage')->getToken());
        $this->data['page'] = [ 'title' => 'Dashboard' ];
        $this->data['dashboard'] = [
            'draftCount' => $entityManager->getRepository(Page::class)->getAllCount([
                'q.status = :status',
                [
                    'status' => Page::DRAFT,
                ],
            ]),
            'publishCount' => 0,
            'upcomingCount' => 0,
            'drafts' => [],
            'upcoming' => [],
            'posts' => [],
        ];
//            'draftCount' => $pageManager->getAllCount(array(
//                'q.status = :status',
//                array('status' => Page::DRAFT)
//            )),
//            'publishCount' => $pageManager->getAllCount(array(
//                'q.status = :status AND q.postDate <= :postDate',
//                array(
//                    'status' => Page::PUBLISHED,
//                    'postDate' => new \DateTime()
//                )
//            )),
//            'upcomingCount' => $pageManager->getAllCount(array(
//                'q.status = :status AND q.postDate > :postDate',
//                array(
//                    'status' => Page::PUBLISHED,
//                    'postDate' => new \DateTime()
//                )
//            )),
//            'drafts' => $pageManager->getAll(
//                0,
//                5,
//                array(
//                    'q.status = :status',
//                    array('status' => Page::DRAFT)
//                ),
//                'q.postDate ASC, q.modDate'
//            ),
//            'upcoming' => $pageManager->getAll(
//                0,
//                5,
//                array(
//                    'q.status = :status AND q.postDate > :postDate',
//                    array(
//                        'status' => Page::PUBLISHED,
//                        'postDate' => new \DateTime()
//                    )
//                ),
//                'q.postDate ASC, q.modDate'
//            ),
//            'posts' => $pageManager->getAll(
//                0,
//                5,
//                array(
//                    'q.status = :status AND q.postDate <= :postDate',
//                    array(
//                        'status' => Page::PUBLISHED,
//                        'postDate' => new \DateTime()
//                    )
//                ),
//                'q.postDate ASC, q.modDate'
//            )
//        );
        return new Response($this->render('inadmin/dashboard.html.twig', $this->data));
    }

}
