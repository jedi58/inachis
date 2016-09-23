<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Entity\CategoryManager;
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
        $response->body('Blog post controller');
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
        // Confirm URL is for existing content otherwise redirect
        $url = $urlManager->getByUrl(str_replace('/inadmin/', '', $request->server()->get('REQUEST_URI')));
        if (empty($url) && 0 === preg_match(
            '/\/?inadmin\/(post|page)\/new\/?/',
            $request->server()->get('REQUEST_URI')
        )) {
            return $response->redirect(sprintf(
                '/inadmin/%s/new',
                1 === preg_match(
                    '/\/?[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*/',
                    $request->server()->get('REQUEST_URI')
                ) ? 'post' : 'page'
            ))->send();
        }
        if ($response->isLocked()) {
            return null;
        }
        self::adminInit($request, $response);
        $pageManager = new PageManager(Application::getInstance()->getService('em'));
        $post = !empty($url) ? $url->getContent() : $post = $pageManager->create();
        if ($request->method('post')) {
            $post = $pageManager->hydrate($post, $request->paramsPost()->all());
            $post->setAuthor(
                Application::getInstance()->getService('auth')->getUserManager()->getById(
                    Application::getInstance()->getService('session')->get('user')->getId()
                )
            );
            $categoryManager = new CategoryManager(Application::getInstance()->getService('em'));
            $tagManager = new TagManager(Application::getInstance()->getService('em'));
            $categories = $request->paramsPost()->get('categories');
            foreach ($categories as $categoryId) {
                $category = $categoryManager->getById($categoryId);
                $post->addCategory($category);
            }
            $tags = $request->paramsPost()->get('tags');
            foreach ($tags as $tagTitle) {
                $tag = $tagManager->getByTitle($tagTitle);
                if (null === $tag) {
                    $tag = $tagManager->create(array('title' => $tagTitle));
                }
                $post->addTag($tag);
            }
            $newUrl = $request->paramsPost()->get('url');
            $urlFound = false;
            $urls = $post->getUrls();
            foreach ($urls as $url) {
                if ($url->getLink() !== $newUrl) {
                    $url->setDefault(false);
                } else {
                    $urlFound = true;
                }
            }
            if (!$urlFound) {
                $post->addUrl($urlManager->create(array(
                    'content' => $post,
                    'default' => true,
                    'link' => $newUrl
                )));
            }
            var_dump($request->paramsPost()->all());
            // @todo if publish button clicked then change from draft
            // @todo validate post
            if (empty(self::$errors)) {
                //$pageManager->save($post);
                //$response->redirect('/inadmin/' . $post->getUrls()[0]);
            }
            // @todo save post
            // @todo if post has an ID check if the URL has changed, if so change the default flag to 0 for the old one
            // @todo header redirect to view current post (need to add view post link to template)
        }
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
        $response->body($app->twig->render('admin__post-list.html.twig', self::$data));
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
     * @Route("/inadmin/page/[:pageTitle]")
     * @Method({"GET", "POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     * @return mixed
     */
    public static function getPageAdmin($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
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
        $response->body('Show settings page for signed in admin');
    }
}
