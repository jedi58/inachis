<?php

namespace Inachis\Component\CoreBundle\Controller;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Entity\CategoryManager;

class AdminDialogController extends AbstractController
{
    /**
     * @Route("/inadmin/ax/categoryManager/get")
     * @Method({"POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     */
    public static function getCategoryManagerContent($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return;
        }
        $categoryManager = new CategoryManager(Application::getInstance()->getService('em'));
        self::$data['categories'] = $categoryManager->getRootCategories();
        $response->body($app->twig->render('admin__dialog__categoryManager.html.twig', self::$data));
    }
    /**
     * @Route("/inadmin/ax/categoryManager/save")
     * @Method({"POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     */
    public static function saveCategoryManagerContent($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return;
        }
        $categoryManager = new CategoryManager(Application::getInstance()->getService('em'));
        $newCategory = $categoryManager->create(array(
            'title' => $request->paramsPost()->get('categoryName'),
            'parent' => $categoryManager->getById($request->paramsPost()->get('parentId'))
        ));
        $categoryManager->save($newCategory);
    }
}
