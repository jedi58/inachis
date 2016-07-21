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
use Inachis\Component\CoreBundle\Form\Fields\SelectType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionType;
use Inachis\Component\CoreBundle\Form\Fields\SelectOptionGroupType;
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
        $response->body('Blog post admin controller');
    }
    public static function getPage($request, $response, $service, $app)
    {
        $response->body('Page controller');
        //throw \Klein\Exceptions\HttpException::createFromCode(404);
    }

    public static function getPageAdmin($request, $response, $service, $app)
    {
        $response->body('Page admin controller');
    }
}
