<?php

namespace Inachis\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends Controller
{
    /**
     * @Route("/inadmin", methods={"GET"})
     */
    public function default()
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        if ($response->isLocked()) {
//            return;
//        }
//        $pageManager = new PageManager(Application::getInstance()->getService('em'));
//        self::adminInit($request, $response);
//        self::$data['page'] = array('title' => 'Dashboard');
//        self::$data['data'] = array(
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
        return $this->render('inadmin/dashboard.html.twig', self::$data);
    }

}
