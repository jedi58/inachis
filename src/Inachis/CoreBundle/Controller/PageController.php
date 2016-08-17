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
     */
    public static function getPost($request, $response, $service, $app)
    {
        $response->body('Blog post controller');
    }

    public static function getPostAdmin($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        $urlManager = new UrlManager(Application::getInstance()->getService('em'));
        // Confirm URL is for existing content otherwise redirect
        $url = $urlManager->getByUrl($request->server()->get('REQUEST_URI'));
        if (empty($url) && 0 === preg_match(
            '/\/?inadmin\/(post|page)\/new\/?/',
            $request->server()->get('REQUEST_URI')
        )) {
            $response->redirect(sprintf(
                '/inadmin/%s/new',
                1 === preg_match(
                    '/\/?[0-9]{4}\/[0-9]{2}\/[0-9]{2}\/.*/',
                    $request->server()->get('REQUEST_URI')
                ) ? 'post' : 'page'
            ))->send();
        }
        $pageManager = new PageManager(Application::getInstance()->getService('em'));
        $properties = $request->paramsPost()->all();
        if (!empty($url) & !empty($properties)) {
//            $post = $pageManager->hydrate($pageManager->getById($url->getContentId()), $properties);
        }
        if (empty($post) || empty($post->getId())) {
            $post = $pageManager->create($properties);
        }

        $categoryManager = new CategoryManager(Application::getInstance()->getService('em'));
        $tagManager = new TagManager(Application::getInstance()->getService('em'));
        // Get default URL as this is the "live" one
        //$url = $urlManager->getDefaultUrlByContentTypeAndId('Page', $post->getId());
        // get all categories and tags
        if ($request->method() == 'POST') {
            $post->setAuthor(
                Application::getInstance()->getService('auth')->getUserManager()->getById(
                    Application::getInstance()->getService('session')->get('user')->getId()
                )
            );
            if (empty(self::$errors)) {
                // save URL, tags and category
                $url = $urlManager->create(array(
                    'content' => $post,
                    'link' => $post->getPostDateAsLink() . $urlManager->urlify($post->getTitle())
                ));
                $urlManager->save($url);
                $pageManager->save($post);
                Application::getInstance()->getService('em')->flush();
var_dump($post);
exit;

                //$pageManager->save($post);
                //$respone->redirect();
            }
        }
        $data = array(
            'post' => $post,
            'form' => (new FormBuilder(array(
                'action' => $request->server()->get('REQUEST_URI'),
                'cssClasses' => 'form form__post'
            )))
            ->addComponent(new FieldsetType(array(
                'legend' => 'Edit Post'
            )))
            ->addComponent(new TextType(array(
                'name' => 'title',
                'autofocus' => true,
                'cssClasses' => 'field__wide',
                'label' => 'Post Title',
                'placeholder' => 'e.g. My first blog post',
                'required' => true,
                'value' => $post->getTitle()
            )))
            ->addComponent(new TextType(array(
                'name' => 'subTitle',
                'cssClasses' => 'field__wide',
                'label' => 'Sub-title',
                'value' => $post->getSubTitle()
            )))
            ->addComponent(new TextAreaType(array(
                'name' => 'content',
                'value' => $post->getContent()
            )))
            ->addComponent(new TextType(array(
                'name' => 'link',
                'cssClasses' => 'field__wide',
                'label' => 'URL',
                'required' => true,
                'value' => $post->getUrls()
            )))
            ->addComponent(new SelectType(array(
                'name' => 'tags',
                'cssClasses' => 'field__wide js-select',
                'label' => 'Tags',
                //'value' => $post->getTags()
            )))
            ->addComponent(new NumberType(array(
                'name' => 'postDate',
                'cssClasses' => '',
                'label' =>'Publication Date',
                'type' => 'datetime'
            )))
            ->addComponent(new ButtonType(array(
                'label' => !empty($post->getId()) ? 'Update' : 'Save',
                'cssClasses' => 'button button--positive',
                'type' => 'submit'
            ))),
            //featureImage
            //featureSnippet
            //status
            //visibility
            //postDate
            //password
            //allowComments
            'includeEditor' => true,
            'session' => $_SESSION
        );
        $response->body($app->twig->render('admin__post__edit.html.twig', $data));
    }
    public static function getPage($request, $response, $service, $app)
    {
        $response->body('Page controller');
        //throw \Klein\Exceptions\HttpException::createFromCode(404);
    }
    /**
     * @Route("/inadmin/page/[:pageTitle]")
     * @Method({"GET", "POST"})
     */
    public static function getPageAdmin($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
    }
    /**
     * @Route("/inadmin/search/results")
     * @Method({"POST"})
     */
    public static function getSearchResults($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        $response->body('Show settings page for signed in admin');
    }
}
