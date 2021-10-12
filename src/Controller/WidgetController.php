<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Series;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WidgetController extends AbstractController
{
    /*
     * @var int Default number of items to be shown by "widgets"
     */
    const DEFAULT_MAX_DISPLAY_COUNT = 10;

    /**
     * @param int $maxDisplayCount
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRecentTrips($maxDisplayCount = self::DEFAULT_MAX_DISPLAY_COUNT)
    {
        return $this->render('web/partials/recent_trips.html.twig', [
            'trips' => $this->getRecentSeries($maxDisplayCount),
        ]);
    }

    /**
     * @param int $maxDisplayCount
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRecentRunning($maxDisplayCount = self::DEFAULT_MAX_DISPLAY_COUNT)
    {
        return $this->render('web/partials/recent_running.html.twig', [
            'races' => $this->getPagesWithCategoryName('Running'),

        ]);
    }

    /**
     * @param int $maxDisplayCount
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getRecentArticles($maxDisplayCount = self::DEFAULT_MAX_DISPLAY_COUNT)
    {
        return $this->render('web/partials/recent_articles.html.twig', [
            'articles' => $this->getPagesWithCategoryName('Articles'),
        ]);
    }

    /**
     * @param $categoryName
     * @return Page[]
     */
    private function getPagesWithCategoryName($categoryName)
    {
        $doctrineManager = $this->getDoctrine()->getManager();
        $category = $doctrineManager->getRepository(Category::class)->findOneByTitle($categoryName);
        if ($category instanceof Category) {
            return $doctrineManager->getRepository(Page::class)->getPagesWithCategory($category);
        }
        return [];
    }

    private function getRecentSeries(int $maxDisplayCount = null)
    {
        $doctrineManager = $this->getDoctrine()->getManager();
        return $doctrineManager->getRepository(Series::class)->findBy([], ['lastDate' => 'DESC'], $maxDisplayCount);
    }
}
