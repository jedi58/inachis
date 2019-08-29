<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Revision;
use App\Entity\Tag;
use App\Entity\Url;
use App\Form\PostType;
use App\Repository\RevisionRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ZZPageController extends AbstractInachisController
{
    /**
     * @Route(
     *     "/{year}/{month}/{day}/{title}",
     *     methods={"GET"},
     *     requirements={
     *          "year": "\d+",
     *          "month": "\d+",
     *          "day": "\d+"
     *     }
     * )
     * @param Request $request
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string $title
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function getPost(Request $request, $year, $month, $day, $title)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $url = $entityManager->getRepository(Url::class)->findOneByLink(
            ltrim($request->getRequestUri(), '/')
        );
        if (empty($url)) {
            throw new NotFoundHttpException(
                sprintf(
                    '%s does not exist',
                    ltrim($request->getRequestUri(), '/')
                )
            );
        }
        if ($url->getContent()->isScheduledPage() || $url->getContent()->isDraft()) {
            return $this->redirectToRoute(
                'app_dashboard_default',
                []
            );
        }
        if (!$url->isDefault()) {
            $url = $entityManager->getRepository(Url::class)->getDefaultUrl($url->getContent());
            if (!empty($url) && $url->isDefault()) {
                return new RedirectResponse($url->getLink(), Response::HTTP_PERMANENTLY_REDIRECT);
            }
        }
        $this->data['post'] = $url->getContent();
        $this->data['url'] = $url->getLink();
        return $this->render('web/post.html.twig', $this->data);
    }

    /**
     * @Route(
     *     "/incc/{type}/list/{offset}/{limit}",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "type": "post|page",
     *          "offset": "\d+",
     *          "limit"="\d+"
     *     },
     *     defaults={"offset"=0, "limit"=10}
     * )
     * @param Request $request
     * @param string $type
     * @return null
     * @throws \Exception
     */
    public function getPostListAdmin(Request $request, $type = 'post')
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder()->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !empty($request->get('items'))) {
            foreach ($request->get('items') as $item) {
                if ($request->request->has('delete')) {
                    $post = $entityManager->getRepository(Page::class)->findOneById($item);
                    if ($post !== null) {
                        $entityManager->getRepository(Page::class)->remove($post);
                        $entityManager->getRepository(Revision::class)->deleteAndRecordByPage($post);
                    }
                }
                if ($request->request->has('private') || $request->request->has('public')) {
                    $post = $entityManager->getRepository(Page::class)->findOneById($item);
                    if ($post !== null) {
                        $post->setVisibility(
                            $request->request->has('private') ? Page::VIS_PRIVATE : Page::VIS_PUBLIC
                        );
                        $post->setModDate(new \DateTime('now'));
                        $entityManager->persist($post);
                    }
                }
            }
            if ($request->request->has('private') || $request->request->has('public')) {
                $revision = $entityManager->getRepository(Revision::class)->hydrateNewRevisionFromPage($post);
                $revision = $revision
                    ->setContent('')
                    ->setAction(sprintf(RevisionRepository::VISIBILITY_CHANGE, $post->getVisibility()));
                $entityManager->persist($revision);

                $entityManager->flush();
            }
            return $this->redirectToRoute(
                'app_zzpage_getpostlistadmin',
                [ 'type' => $type ]
            );
        }
        $offset = (int) $request->get('offset', 0);
        $limit = $entityManager->getRepository(Page::class)->getMaxItemsToShow();
        $this->data['form'] = $form->createView();
        $this->data['posts'] = $entityManager->getRepository(Page::class)->getAll(
            $offset,
            $limit,
            [
                'q.type = :type',
                [
                    'type' => $type,
                ]
            ],
            [
                [ 'q.postDate', 'DESC' ],
                [ 'q.modDate', 'DESC' ]
            ]
        );
        $this->data['page']['offset'] = $offset;
        $this->data['page']['limit'] = $limit;
        $this->data['page']['tab'] = $type;
        $this->data['page']['title'] = ucfirst($type) . 's';
        return $this->render('inadmin/post__list.html.twig', $this->data);
    }

    /**
     * @Route(
     *     "/incc/{type}/{title}",
     *     methods={"GET", "POST"},
     *     defaults={"type": "post"},
     *     requirements={
     *          "type": "post|page"
     *     }
     * )
     * @Route(
     *     "/incc/{type}/{year}/{month}/{day}/{title}",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "type": "post",
     *          "year": "\d+",
     *          "month": "\d+",
     *          "day": "\d+"
     *     }
     * )
     *
     * @param Request $request
     * @param string $type
     * @param string $title
     * @return mixed
     * @throws \Exception
     *
     * @return mixed
     */
    public function getPostAdmin(Request $request, $type = 'post', $title = null)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $url = preg_replace('/\/?incc\/(page|post)\/?/', '', $request->getRequestUri());
        $url = $entityManager->getRepository(Url::class)->findByLink($url);
        $title = $title === 'new' ? null : $title;
        // If content with this URL doesn't exist, then redirect
        if (empty($url) && null !== $title) {
            return $this->redirectToRoute(
                'app_zzpage_getpostadmin',
                ['type' => $type]
            );
        }
        $post = null !== $title ?
            $entityManager->getRepository(Page::class)->findOneById($url[0]->getContent()->getId()) :
            $post = new Page();
        if ($post->getId() === null) {
            $post->setType($type);
        }
        if (!empty($post->getId())) {
            $revision = $entityManager->getRepository(Revision::class)->hydrateNewRevisionFromPage($post);
            $revision = $revision->setAction(RevisionRepository::UPDATED);
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {//} && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->getRepository(Page::class)->remove($post);
                $entityManager->getRepository(Revision::class)->deleteAndRecordByPage($post);
                return $this->redirectToRoute(
                    'app_dashboard_default',
                    [],
                    Response::HTTP_PERMANENTLY_REDIRECT
                );
            }
            $post->setAuthor($this->get('security.token_storage')->getToken()->getUser());
            if (null !== $request->get('publish')) {
                $post->setStatus(Page::PUBLISHED);
            }
            if (!empty($request->get('post')['url'])) {
                $newUrl = $request->get('post')['url'];
                $urlFound = false;
                if (!empty($post->getUrls())) {
                    foreach ($post->getUrls() as $url) {
                        if ($url->getLink() !== $newUrl) {
                            $url->setDefault(false);
                        } else {
                            $urlFound = true;
                        }
                    }
                }
                if (!$urlFound) {
                    new Url($post, $newUrl);
                }
            }
            $post = $post->removeCategories()->removeTags();
            if (!empty($request->get('post')['categories'])) {
                $newCategories = $request->get('post')['categories'];
                if (!empty($newCategories)) {
                    foreach ($newCategories as $newCategory) {
                        $category = $entityManager->getRepository(Category::class)->findOneById($newCategory);
                        if (!empty($category)) {
                            $post->getCategories()->add($category);
                        }
                    }
                }
            }
            if (!empty($request->get('post')['tags'])) {
                $newTags = $request->get('post')['tags'];
                if (!empty($newTags)) {
                    foreach ($newTags as $newTag) {
                        $tag = $entityManager->getRepository(Tag::class)->findOneById($newTag);
                        if (empty($tag)) {
                            $tag = new Tag($newTag);
                        }
                        $post->getTags()->add($tag);
                    }
                }
            }

            if ($form->get('publish')->isClicked()) {
                $post->setStatus(Page::PUBLISHED);
            }

            $post->setModDate(new \DateTime('now'));
            if (!empty($post->getId())) {
                $entityManager->persist($revision);
            }
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('notice', 'Content saved.');
            return $this->redirect(
                '/incc/' .
                $post->getType() . '/' .
                $post->getUrls()[0]->getLink()
            );
        }

        $this->data['form'] = $form->createView();
        $this->data['page']['tab'] = $post->getType();
        $this->data['page']['title'] = $post->getId() !== null ?
            'Editing "' . $post->getTitle() . '"' :
            'New ' . $post->getType();
        $this->data['includeEditor'] = true;
        $this->data['includeEditorId'] = $post->getId();
        $this->data['includeDatePicker'] = true;
        $this->data['post'] = $post;
        return $this->render('inadmin/post__edit.html.twig', $this->data);
    }

    /**
     * @Route("/incc/search/results", methods={"GET", "POST"})
     */
    public function getSearchResults()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        return new Response('<html><body>Show search results</body></html>');
    }

    /**
     * @Route(
     *     "/{page}",
     *     methods={"GET"}
     * )
     * @param Request $request
     * @return mixed
     */
    public function getPage(Request $request)
    {
        return $this->getPost($request, 0, 0, 0, '');
    }

    /**
     * Returns `page` or `post` depending on the current URL
     * @param Request $request
     * @return string The result of testing the current URL
     */
    private function getContentType(Request $request)
    {
        return 1 === preg_match(
            '/\/incc\/([0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*|post)/',
            $request->server()->get('REQUEST_URI')
        ) ? Page::TYPE_POST : Page::TYPE_PAGE;
    }
}
