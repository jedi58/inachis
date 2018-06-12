<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Tag;
use App\Entity\Url;
use App\Form\PostType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ZZPageController extends AbstractInachisController
{
//     * @Route("/{slug}", methods={"GET"})
//    public function getPage($slug)
//    {
//        return new Response('<html><body>Page controller</body></html>');
//        //throw $this->createNotFoundException('This page does not exist');
//    }

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
                sprintf('%s does not exist',
                ltrim($request->getRequestUri(), '/'))
            );
        }
        if ($url->getContent()->isScheduledPage() || $url->getContent()->isDraft()) {
            return $this->redirectToRoute(
                'app_dashboard_default',
                [],
                Response::HTTP_TEMPORARY_REDIRECT
            );
        }
        if (!$url->isDefault()) {
            $url = $entityManager->getRepository(Url::class)->getDefaultUrl($url->getContent());
            if (!empty($url) && $url->isDefault()) {
                return new RedirectResponse($url->getLink(), Response::HTTP_PERMANENTLY_REDIRECT);
            }
        }

        // Get X from page
        //Get X from series
        //If page.id IN series ignore
        //combine into array with date + title as key
        //key sort

        $data['post'] = $url->getContent();
        $data['url'] = $url->getLink();
        return $this->render('web/post.html.twig', $data);

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
        // If content with this URL doesn't exist, then redirect
        if (empty($url) && null !== $title) {
            return $this->redirectToRoute(
                'app_zzpage_getpostadmin',
                ['type' => $type],
                Response::HTTP_TEMPORARY_REDIRECT
            );
        }
        $post = null !== $title ?
            $entityManager->getRepository(Page::class)->findOneById($url[0]->getContent()->getId()) :
            $post = new Page();
        if ($post->getId() === null) {
            $post->setType($type);
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {//} && $form->isValid()) {
            if ($form->get('delete')->isClicked()) {
                $entityManager->getRepository(Page::class)->remove($post);
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
            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('notice', 'Content saved.');
            return $this->redirect(
                '/incc/' .
                ( $post->getType() == Page::TYPE_PAGE ? 'page/' : '' ) .
                $post->getUrls()[0]->getLink()
            );
        }

        $this->data['form'] = $form->createView();
        $this->data['page']['tab'] = $post->getType();
        $this->data['page']['title'] = $post->getId() !== null ?
            'Editing "' . $post->getTitle() . '"' :
            'New ' . $post->getType();
        $this->data['includeEditor'] = true;
        $this->data['post'] = $post;
        return $this->render('inadmin/post__edit.html.twig', $this->data);
    }

    /**
     * @Route(
     *     "/incc/{type}/list/",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "type": "post|page"
     *     }
     * )
     * @param string $type
     * @return null
     */
    public function getPostListAdmin(Request $request, $type = 'post')
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createFormBuilder(null);
        //$form = $this->createForm(PostType::class);
        //$form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            if ($form->get('delete')->isClicked()) {
//                $entityManager->getRepository(Page::class)->remove($post);
//                return new RedirectResponse(
//                    sprintf('/incc/%s/list/', $type),
//                    HTTP_REDIRECT_PERM
//                );
//            }
//        }
        $offset = 0;
        $this->data['form'] = $form->getForm()->createView();
        $this->data['posts'] = $entityManager->getRepository(Page::class)->getAll(
            $offset,
            10,
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
        $this->data['page']['limit'] = 10;
        $this->data['page']['tab'] = $type;
        $this->data['page']['title'] = ucfirst($type) . 's';
        return $this->render('inadmin/post__list.html.twig', $this->data);
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
