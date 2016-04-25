<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Entity\PageManager;
use Inachis\Component\CoreBundle\Entity\UrlManager;

class PageController
{
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
