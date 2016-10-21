<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Entity\CategoryManager;
use Inachis\Component\CoreBundle\Entity\Page;
use Inachis\Component\CoreBundle\Entity\PageManager;
use Inachis\Component\CoreBundle\Entity\TagManager;
use Inachis\Component\CoreBundle\Entity\UrlManager;
use Inachis\Component\CoreBundle\Entity\Url;
use Inachis\Component\CoreBundle\Entity\UserManager;
use Inachis\Component\CoreBundle\Form\FormBuilder;
use Inachis\Component\CoreBundle\Form\Fields\ButtonType;
use Inachis\Component\CoreBundle\Form\Fields\ChoiceType;
use Inachis\Component\CoreBundle\Form\Fields\FieldsetType;
use Inachis\Component\CoreBundle\Form\Fields\FileUploadType;
use Inachis\Component\CoreBundle\Form\Fields\HiddenType;
use Inachis\Component\CoreBundle\Form\Fields\NumberType;
//use Inachis\Component\CoreBundle\Form\Fields\ReCaptchaType;
use Inachis\Component\CoreBundle\Form\Fields\SelectType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionGroupType;
//use Inachis\Component\CoreBundle\Form\Fields\TableType;
use Inachis\Component\CoreBundle\Form\Fields\TextType;
use Inachis\Component\CoreBundle\Form\Fields\TextAreaType;

class PageController extends AbstractController
{
    /**
     * @Route("/setup")
     * @Method({"GET", "POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getPost($request, $response, $service, $app)
    {
        $urlManager = new UrlManager(Application::getInstance()->getService('em'));
        $url = $urlManager->getByUrl($request->server()->get('REQUEST_URI'));
        if (empty($url)) {
            return $response->code(404);
        }
        if ($url->getContent()->isScheduledPage() || $url->getContent()->isDraft()) {
            return $response->redirect('/');
        }
        if (!$url->getDefault()) {
            $url = $urlManager->getDefaultUrl($url->getContent());
            if (!empty($url)) {
                return $response->redirect($url->getLink(), 301);
            }
        }
/** end block **/
        $userManager = new UserManager(Application::getInstance()->getService('em'));
        $userManager->getByUsername($url->getContent()->getAuthor()->getUsername());
        $data = array(
            'post' => $url->getContent(),
            'url' => $url->getLink()
        );
        return $response->body($app->twig->render('post.html.twig', $data));
    }

    /**
     * Renders the view for managing the {@link Page model}
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getPostAdmin($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        $urlManager = new UrlManager(Application::getInstance()->getService('em'));
        $requestUri = preg_replace('/\/inadmin\/(page\/)?/', '', $request->server()->get('REQUEST_URI'));
        if ($requestUri === 'list') {
            return null;
        }
        $url = $urlManager->getByUrl($requestUri);
        if (empty($url) && 0 === preg_match(
            '/\/?inadmin\/(post|page)\/new\/?/',
            $request->server()->get('REQUEST_URI')
        )) {
            return $response->redirect(sprintf(
                '/inadmin/%s/new',
                self::getContentType($request)
            ))->send();
        }
        if ($response->isLocked()) {
            return null;
        }
        self::adminInit($request, $response);
        $pageManager = new PageManager(Application::getInstance()->getService('em'));
        $post = !empty($url) ? $pageManager->getById($url->getContent()->getId()) : $post = $pageManager->create();
        if ($post->getId() === null) {
            $post->setType(self::getContentType($request));
        }
        if ($request->method('post')) {
            $properties = $request->paramsPost()->all();
            $properties['postDate'] = new \DateTime(
                $properties['postDate'],
                new \DateTimeZone($post->getTimezone())
            );
            $post = $pageManager->hydrate($post, $properties);
            $post->setAuthor(
                Application::getInstance()->getService('auth')->getUserManager()->getByIdRaw(
                    Application::getInstance()->getService('session')->get('user'))
            );
            $categoryManager = new CategoryManager(Application::getInstance()->getService('em'));
            $tagManager = new TagManager(Application::getInstance()->getService('em'));
            $categories = $request->paramsPost()->get('categories');
            $assignedCategories = $post->getCategories()->getValues();
            if (!empty($categories)) {
                foreach ($categories as $categoryId) {
                    $category = $categoryManager->getById($categoryId);
                    if (in_array($category, $assignedCategories)) {
                        continue;
                    }
                    $post->addCategory($category);
                }
            }
            $tags = $request->paramsPost()->get('tags');
            $assignedTags = $post->getTags()->getValues();
            if (!empty($tags)) {
                foreach ($tags as $tagTitle) {
                    $tag = $tagManager->getByTitle($tagTitle);
                    if (in_array($tag, $assignedTags)) {
                        continue;
                    }
                    if (null === $tag) {
                        $tag = $tagManager->create(array('title' => $tagTitle));
                    }
                    $post->addTag($tag);
                }
            }
            $newUrl = $request->paramsPost()->get('url');
            $urlFound = false;
            $urls = $post->getUrls();
            if (!empty($urls)) {
                foreach ($urls as $url) {
                    if ($url->getLink() !== $newUrl) {
                        $url->setDefault(false);
                    } else {
                        $urlFound = true;
                    }
                }
            }
            if (!$urlFound) {
                $post->addUrl($urlManager->create(array(
                    'content' => $post,
                    'default' => true,
                    'link' => $newUrl
                )));
            }
            if (null !== $request->paramsPost()->get('publish')) {
                $post->setStatus(Page::PUBLISHED);
            }
            $post->setVisibility(Page::VIS_PRIVATE);
            if ($request->paramsPost()->get('visibility') === 'on') {
                $post->setVisibility(Page::VIS_PUBLIC);
            }
            // @todo validate post
            if (empty(self::$errors)) {
                $pageManager->save($post);
                return $response->redirect(
                    '/inadmin/' .
                    ($post->getType() == Page::TYPE_PAGE ? 'page/' : '') .
                    $post->getUrls()[0]->getLink()
                );
            }
        }
        self::$data['page']['title'] = $post->getId() !== null ?
            'Editing "' . $post->getTitle() . '"' :
            'New ' . $post->getType();
        self::$data['includeEditor'] = true;
        self::$data['post'] = $post;
        return $response->body($app->twig->render('admin__post__edit.html.twig', self::$data));
    }

    /**
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getPostListAdmin($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return;
        }
        self::adminInit($request, $response);
        return $response->body($app->twig->render('admin__post-list.html.twig', self::$data));
    }

    /**
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getPage($request, $response, $service, $app)
    {
        $response->body('Page controller');
        //throw \Klein\Exceptions\HttpException::createFromCode(404);
    }

    /**
     * @Route("/inadmin/search/results")
     * @Method({"POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getSearchResults($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        return $response->body('Show settings page for signed in admin');
    }

    /**
     * Returns `page` or `post` depending on the current URL
     * @param \Klein\Request $request
     * @return string The result of testing the current URL
     */
    public static function getContentType($request)
    {
        return 1 === preg_match(
            '/\/inadmin\/([0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*|post)/',
            $request->server()->get('REQUEST_URI')
        ) ? Page::TYPE_POST : Page::TYPE_PAGE;
    }
}
