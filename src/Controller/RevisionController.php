<?php

namespace App\Controller;

use App\Entity\Page;
use App\Entity\Revision;
use Jfcherng\Diff\DiffHelper;
use Jfcherng\Diff\Factory\RendererFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class RevisionController extends AbstractInachisController
{
    /**
     * @Route("/incc/page/diff/{id}", methods={"GET"})
     */
    public function diff(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $revision = $entityManager->getRepository(Revision::class)->findOneById(
            $request->get('id')
        );
        if (empty($revision) || empty($revision->getPageId())) {
            throw new NotFoundHttpException(
                sprintf('Version history could not be found for %s', $request->get('id'))
            );
        }
        $page = $entityManager->getRepository(Page::class)->findOneById($revision->getPageId());
        $trackChanges = [
            'content' => json_decode(DiffHelper::calculate(
                $revision->getContent(),
                $page->getContent(),
                'Json',
                [
                    'context' => 0,
                ],
                [
                    'outputTagAsString' => true,
                ]
            )),
        ];

        $this->data['page']['title'] = 'Compare Revisions';
        $this->data['title'] = json_decode(
            DiffHelper::calculate($revision->getTitle(), $page->getTitle(), 'Json', [], [
                'detailLevel' => 'word',
                'outputTagAsString' => true,
            ])
        );
        if (empty($this->data['title'])) {
            $this->data['title'] = $page->getTitle();
        }
        $this->data['subTitle'] = json_decode(
            DiffHelper::calculate($revision->getSubTitle(), $page->getSubTitle(), 'Json')
        );
        if (empty($this->data['subTitle'])) {
            $this->data['subTitle'] = $page->getSubTitle();
        }
        $this->data['content'] = mb_split(PHP_EOL, $revision->getContent());
        foreach ($trackChanges['content'] as $changeGroup) {
            foreach ($changeGroup as $change) {
                if (in_array($change->tag, [ 'rep', 'del' ])) {
                    $this->data['content'][$change->old->offset] = $change;
                }
            }
        }

        return $this->render('inadmin/track_changes.html.twig', $this->data);
    }
}
