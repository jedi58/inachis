<?php

namespace App\Repository;

use App\Entity\Series;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Series|null find($id, $lockMode = null, $lockVersion = null)
 * @method Series|null findOneBy(array $criteria, array $orderBy = null)
 * @method Series[]    findAll()
 * @method Series[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeriesRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Series::class);
    }

    public function getSeriesByPost($page)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->leftJoin('s.items', 'Series_pages')
            ->where('Series_pages.id = :pageId')
            ->setParameter('pageId', $page->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }
}
