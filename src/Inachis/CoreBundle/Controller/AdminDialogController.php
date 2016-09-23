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
     * @Route("/inadmin/ax/categoryList/get")
     * @Method({"POST"})
     * @param \Klein\Request $request
     * @param \Klein\Response $response
     * @param \Klein\ServiceProvider $service
     * @param \Klein\App $app
     */
    public static function getCategoryListContent($request, $response, $service, $app)
    {
        self::redirectIfNotAuthenticated($request, $response);
        if ($response->isLocked()) {
            return;
        }
        $categoryManager = new CategoryManager(Application::getInstance()->getService('em'));
        self::$data['categories'] = empty($request->paramsPost()->get('q')) ?
            $categoryManager->getRootCategories() :
            $categoryManager->getAll(
                0,
                25,
                array(
                    'q.title LIKE :title',
                    array('title' => '%' . $request->paramsPost()->get('q') . '%')
                ),
                'q.title'
            );
        self::$data['totalResults'] = $categoryManager->getAllCount(array(
            'q.title LIKE :title',
            array('title' => '%' . $request->paramsPost()->get('q') . '%')
        ));
        $result = array(
            'items' => array(),
            'totalCount' => self::$data['totalResults']
        );
        if (!empty(self::$data['categories'])) {
            foreach (self::$data['categories'] as $category) {
                $title = $category->getTitle();
                if (isset($result['items'][$title])) {
                    $result['items'][$result['items'][$title]->path] = $result['items'][$title];
                    $result['items'][$result['items'][$title]->path]->text = $result['items'][$title]->path;
                    unset($result['items'][$title]);
                    $title = $category->getFullPath();
                }
                $result['items'][$title] = (object) array(
                    'id' => $category->getId(),
                    'text' => $title,
                    'path' => $category->getFullPath()
                );
            }
            $result['items'] = array_values($result['items']);
        }
        $response->body(json_encode((object) $result));
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
