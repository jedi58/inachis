<?php

namespace App\Controller;

use App\Controller\AbstractInachisController;
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
     * @Route(
     *     "/incc/{type}/new",
     *     methods={"GET", "POST"},
     *     defaults={"type": "post"},
     *     requirements={
     *          "type": "post|page"
     *     }
     * )
     * @return mixed
     */
    public function getPostAdmin($type, $title)
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        $urlManager = new UrlManager(Application::getInstance()->getService('em'));
//        $requestUri = preg_replace('/\/inadmin\/(page\/)?/', '', $request->server()->get('REQUEST_URI'));
//        if ($requestUri === 'list') {
//            return null;
//        }
//        $url = $urlManager->getByUrl($requestUri);
//        if (empty($url) && 0 === preg_match(
//                '/\/?inadmin\/(post|page)\/new\/?/',
//                $request->server()->get('REQUEST_URI')
//            )) {
//            return $response->redirect(sprintf(
//                '/inadmin/%s/new',
//                self::getContentType($request)
//            ))->send();
//        }
//        if ($response->isLocked()) {
//            return null;
//        }
//        self::adminInit($request, $response);
//        $pageManager = new PageManager(Application::getInstance()->getService('em'));
//        $post = !empty($url) ? $pageManager->getById($url->getContent()->getId()) : $post = $pageManager->create();
//        if ($post->getId() === null) {
//            $post->setType(self::getContentType($request));
//        }
//        if ($request->method('post')) {
//            $properties = $request->paramsPost()->all();
//            $properties['postDate'] = new \DateTime(
//                $properties['postDate'],
//                new \DateTimeZone($post->getTimezone())
//            );
//            if (null !== $request->paramsPost()->get('delete') && !empty($post->getId())) {
//                foreach ($post->getUrls() as $postUrl) {
//                    $urlManager->remove($postUrl);
//                }
//                $pageManager->remove($post);
//                return $response->redirect('/inadmin/');
//            }
//            $post = $pageManager->hydrate($post, $properties);
//            $post->setAuthor(
//                Application::getInstance()->getService('auth')->getUserManager()->getByIdRaw(
//                    Application::getInstance()->getService('session')->get('user'))
//            );
//            $categoryManager = new CategoryManager(Application::getInstance()->getService('em'));
//            $tagManager = new TagManager(Application::getInstance()->getService('em'));
//            $categories = $request->paramsPost()->get('categories');
//            $assignedCategories = $post->getCategories()->getValues();
//            if (!empty($categories)) {
//                foreach ($categories as $categoryId) {
//                    $category = $categoryManager->getById($categoryId);
//                    if (in_array($category, $assignedCategories)) {
//                        continue;
//                    }
//                    $post->addCategory($category);
//                }
//            }
//            $tags = $request->paramsPost()->get('tags');
//            $assignedTags = $post->getTags()->getValues();
//            if (!empty($tags)) {
//                foreach ($tags as $tagTitle) {
//                    $tag = $tagManager->getByTitle($tagTitle);
//                    if (in_array($tag, $assignedTags)) {
//                        continue;
//                    }
//                    if (null === $tag) {
//                        $tag = $tagManager->create(array('title' => $tagTitle));
//                    }
//                    $post->addTag($tag);
//                }
//            }
//            $newUrl = $request->paramsPost()->get('url');
//            $urlFound = false;
//            $urls = $post->getUrls();
//            if (!empty($urls)) {
//                foreach ($urls as $url) {
//                    if ($url->getLink() !== $newUrl) {
//                        $url->setDefault(false);
//                    } else {
//                        $urlFound = true;
//                    }
//                }
//            }
//            if (!$urlFound) {
//                $post->addUrl($urlManager->create(array(
//                    'content' => $post,
//                    'default' => true,
//                    'link' => $newUrl
//                )));
//            }
//            if (null !== $request->paramsPost()->get('publish')) {
//                $post->setStatus(Page::PUBLISHED);
//            }
//            $post->setVisibility(Page::VIS_PRIVATE);
//            if ($request->paramsPost()->get('visibility') === 'on') {
//                $post->setVisibility(Page::VIS_PUBLIC);
//            }
//            // @todo validate post
//            if (empty(self::$errors)) {
//                $pageManager->save($post);
//                return $response->redirect(
//                    '/inadmin/' .
//                    ($post->getType() == Page::TYPE_PAGE ? 'page/' : '') .
//                    $post->getUrls()[0]->getLink()
//                );
//            }
//        }
//        self::$data['page']['title'] = $post->getId() !== null ?
//            'Editing "' . $post->getTitle() . '"' :
//            'New ' . $post->getType();
//        self::$data['includeEditor'] = true;
//        self::$data['post'] = $post;

        return $this->render('inadmin/post__edit.html.twig', self::$data);
    }

    /**
     * @Route(
     *     "/incc/{type}/list",
     *     methods={"GET", "POST"},
     *     requirements={
     *          "type": "post|page"
     *     }
     * )
     * @return null
     */
    public function getPostListAdmin($type)
    {
//        self::redirectIfNotAuthenticated($request, $response);
//        if ($response->isLocked()) {
//            return null;
//        }
//        self::adminInit($request, $response);
//        $pageManager = new PageManager(Application::getInstance()->getService('em'));
//        $offset = $request->paramsGet()->get('offset', 0);
//        $limit = 10;
//        $type = self::getContentType($request);
//        self::$data['posts'] = $pageManager->getAll(
//            $offset,
//            $limit,
//            array(
//                'q.type = :type',
//                array('type' => $type)
//            ),
//            array(
//                array('q.postDate', 'DESC'),
//                array('q.modDate', 'DESC')
//            )
//        );
//        self::$data['page']['title'] = ucfirst($type) . 's';
        return $this->render('inadmin/post__list.html.twig', self::$data);
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
     * @param Request $request
     * @return string The result of testing the current URL
     */
//    private static function getContentType($request)
//    {
//        return 1 === preg_match(
//            '/\/inadmin\/([0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*|post)/',
//            $request->server()->get('REQUEST_URI')
//        ) ? Page::TYPE_POST : Page::TYPE_PAGE;
//    }
}
