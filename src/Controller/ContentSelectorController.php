<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Series;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContentSelectorController extends AbstractInachisController
{
    /**
     * @var array
     */
    protected $errors = [];
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @Route("/incc/ax/contentSelector/get", methods={"POST"})
     * @param Request $request
     */
    public function contentList(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // @todo paginate data returned with auto-loading from Ajax
        $this->data['pages'] = $this->getDoctrine()->getRepository(Page::class)->findBy([], [
            'title' => 'ASC'
        ]);
        return $this->render('inadmin/dialog/content-selector.html.twig', $this->data);
    }

    /**
     * @Route("/incc/ax/contentSelector/save", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function saveContent(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if (!empty($request->get('ids'))) {
            $series = $this->getDoctrine()->getRepository(Series::class)->findOneById($request->get('seriesId'));
            foreach ($request->get('ids') as $pageId) {
                $page = $this->getDoctrine()->getRepository(Page::class)->findOneById($pageId);
                if (!empty($page) && !empty($page->getId())) {
                    $series->addItem($page);
                }
                $firstDate = $series->getFirstDate();
                $lastDate = $series->getLastDate();
                if ($firstDate === null || $page->getPostDate()->format('Y-m-d H:i:s') < $firstDate->format('Y-m-d H:i:s')) {
                    $series->setFirstDate($page->getPostDate());
                }
                if ($lastDate === null || $page->getPostDate()->format('Y-m-d H:i:s') > $lastDate->format('Y-m-d H:i:s')) {
                    $series->setLastDate($page->getPostDate());
                }
            }
            $series->setModDate(new \DateTime('now'));
            $this->getDoctrine()->getManager()->persist($series);
            $this->getDoctrine()->getManager()->flush();
            return new Response('Saved', Response::HTTP_CREATED);
        }
        return new Response('No change', Response::HTTP_NO_CONTENT);
    }
}
