<?php

namespace App\Controller;

use App\Entity\Series;
use App\Form\SeriesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SeriesController extends AbstractInachisController
{
    /**
     * @Route("/incc/series/list/{offset}/{limit}",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "offset": "\d+",
     *          "limit"="\d+"
     *     },
     *     defaults={"offset"=0, "limit"=10}
     * )
     * @param Request $request
     * @return Response
     */
    public function list(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !empty($request->get('items'))) {
            foreach ($request->get('items') as $item) {
                if ($request->get('delete') !== null) {
                    $post = $entityManager->getRepository(Series::class)->findOneById($item);
                    if ($post !== null) {
                        $entityManager->getRepository(Series::class)->remove($post);
                    }
                }
            }
            return $this->redirectToRoute(
                'app_series_list',
                [],
                Response::HTTP_PERMANENTLY_REDIRECT
            );
        }

        $offset = (int) $request->get('offset', 0);
        $limit = $entityManager->getRepository(Series::class)->getMaxItemsToShow();
        $this->data['form'] = $form->createView();
        $this->data['dataset'] = $entityManager->getRepository(Series::class)->getAll(
            $offset,
            $limit,
            [],
            [
                [ 'q.lastDate', 'DESC' ]
            ]
        );
        $this->data['page']['offset'] = $offset;
        $this->data['page']['limit'] = $limit;
        return $this->render('inadmin/series__list.html.twig', $this->data);
    }

    /**
     * @Route("/incc/series/edit/{id}", methods={"GET", "POST"})
     * @Route("/incc/series/new", methods={"GET", "POST"}, name="app_series_new")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $series = $request->get('id') !== null ?
            $entityManager->getRepository(Series::class)->findOneById($request->get('id')):
            new Series();
        $form = $this->createForm(SeriesType::class, $series);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {//} && $form->isValid()) {
            if (!empty($request->get('series')['image'])) {
                $series->setImage(
                    $entityManager->getRepository(Image::class)->findOneById(
                        $request->get('series')['image']
                    )
                );
            }
            if ($form->get('delete')->isClicked()) {
                // @todo should redirect back to series list
//                $entityManager->getRepository(Series::class)->remove($series);
//                return $this->redirectToRoute(
//                    'app_dashboard_default',
//                    [],
//                    Response::HTTP_PERMANENTLY_REDIRECT
//                );
            }

            $series->setModDate(new \DateTime('now'));
            $entityManager->persist($series);
            $entityManager->flush();

            $this->addFlash('notice', 'Content saved.');
            return $this->redirect(
                '/incc/series/edit/' .
                $series->getId() . '/'
            );
        }

        $this->data['form'] = $form->createView();
        $this->data['page']['title'] = $series->getId() !== null ?
            'Editing "' . $series->getTitle() . '"' :
            'New Series';
        $this->data['series'] = $series;
        $this->data['includeEditor'] = true;
        $this->data['includeEditorId'] = $series->getId();
        $this->data['includeDatePicker'] = true;
        return $this->render('inadmin/series__edit.html.twig', $this->data);
    }

    /**
     * @Route("/{year}-{title}", methods={"GET"})
     * @param Request $request
     * @param int $year
     * @param string $title
     * @return Response
     */
    public function view(Request $request, $year = 0, $title = '')
    {
        $this->data['series'] = $this->entityManager->getRepository(Series::class)->findOneByTitle($title);
        return $this->render('web/series.html.twig', $this->data);
    }
}
