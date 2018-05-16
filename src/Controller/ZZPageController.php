<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Tag;
use App\Entity\Url;
use App\Form\PostType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     */
    public function getPost($year, $month, $day, $title)
    {
//        $urlManager = new UrlManager(Application::getInstance()->getService('em'));
//        $url = $urlManager->getByUrl($request->server()->get('REQUEST_URI'));
//        if (empty($url)) {
//            return $response->code(404);
//        }
//        if ($url->getContent()->isScheduledPage() || $url->getContent()->isDraft()) {
//            return $response->redirect('/');
//        }
//        if (!$url->getDefault()) {
//            $url = $urlManager->getDefaultUrl($url->getContent());
//            if (!empty($url)) {
//                return $response->redirect($url->getLink(), 301);
//            }
//        }
//        $userManager = new UserManager(Application::getInstance()->getService('em'));
//        $userManager->getByUsername($url->getContent()->getAuthor()->getUsername());
        $data = [];/*array(
            'post' => $url->getContent(),
            'url' => $url->getLink()
        );*/
        return $this->render('post.html.twig', $data);

    }

    /**
     * @Route(
     *     "/incc/{type}/new",
     *     methods={"GET", "POST"},
     *     defaults={"type": "post"},
     *     requirements={
     *          "type": "post|page"
     *     }
     * )
     * @Route(
     *     "/incc/{type}/{title}",
     *     methods={"GET", "POST"},
     *     defaults={"type": "post"},
     *     requirements={
     *          "type": "post|page",
     *          "title": "!new"
     *     }
     * )
     * @Route(
     *     "/incc/{year}/{month}/{day}/{title}",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "year": "\d+",
     *          "month": "\d+",
     *          "day": "\d+"
     *     }
     * )
     * @param Request $request
     * @param string $type
     * @param string $title
     * @return mixed
     * @throws \Exception
     */
    public function getPostAdmin(Request $request, $type = 'post', $title = null)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $entityManager = $this->getDoctrine()->getManager();
        $url = str_replace('/incc/', '', $request->getRequestUri());
        $url = $entityManager->getRepository(Url::class)->findByLink($url);
        // If content with this URL doesn't exist, then redirect
        if (empty($url) && null !== $title) {
            return new RedirectResponse(printf(
                '/incc/%s/new',
                $type
            ), HTTP_REDIRECT_TEMP);
        }
        $post = null !== $title ?
            $entityManager->getRepository(Page::class)->findOneById($url[0]->getContent()->getId()) :
            $post = new Page()
        ;
        if ($post->getId() === null) {
            $post->setType($type);
        }
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {//} && $form->isValid()) {
            $post->setAuthor($this->get('security.token_storage')->getToken()->getUser());
            if (null !== $request->get('publish')) {
                $post->setStatus(Page::PUBLISHED);
            }
//            $post->setVisibility(Page::VIS_PRIVATE);
//            if ($request->paramsPost()->get('visibility') === 'on') {
//                $post->setVisibility(Page::VIS_PUBLIC);
//            }
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
            if (!empty($request->get('post')['categories'])) {
                $newCategories = $request->get('post')['categories'];
                if (!empty($newCategories)) {
                    $assignedCategories = $post->getCategories()->getValues();
                    foreach ($newCategories as $newCategory) {
                        $category = $entityManager->getRepository(Category::class)->findOneById($newCategory);
                         if (!empty($category)) {
                            if (in_array($category, $assignedCategories)) {
                                continue;
                            }
                            $post->getCategories()->add($category);
                        }
                    }
                }
            }
            if (!empty($request->get('post')['tags'])) {
                $newTags = $request->get('post')['tags'];
                if (!empty($newTags)) {
                    $assignedTags = $post->getTags()->getValues();
                    foreach ($newTags as $newTag) {
                        $tag = $entityManager->getRepository(Tag::class)->findOneByTitle($newTag);
                        if (in_array($tag, $assignedTags)) {
                            continue;
                        }
                        if (empty($tag)) {
                            $tag = new Tag($newTag);
                        }
                        $post->getTags()->add($tag);
                    }
                }
            }

            if ($form->get('publish')->isClicked()) {

            }

            if ($form->get('delete')->isClicked()) {

            }

            $post->setModDate(new \DateTime('now'));
            $entityManager->merge($post);
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirect(
                '/incc/' .
                ( $post->getType() == Page::TYPE_PAGE ? 'page/' : '' ) .
                $post->getUrls()[0]->getLink()
            );
        }


//            if (null !== $request->paramsPost()->get('delete') && !empty($post->getId())) {
//                foreach ($post->getUrls() as $postUrl) {
//                    $urlManager->remove($postUrl);
//                }
//                $pageManager->remove($post);
//                return $response->redirect('/inadmin/');
//            }

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
        $offset = 0;
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
            ],
            'q.postDate ASC, q.modDate'
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
//        self::redirectIfNotAuthenticated($request, $response);
        return new Response('<html><body>Show search results</body></html>');
    }

    /**
     * Returns `page` or `post` depending on the current URL
     * @return string The result of testing the current URL
     */
    private function getContentType()
    {
        return 1 === preg_match(
            '/\/incc\/([0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*|post)/',
            $request->server()->get('REQUEST_URI')
        ) ? Page::TYPE_POST : Page::TYPE_PAGE;
    }
}
