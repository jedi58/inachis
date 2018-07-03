<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\Url;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UrlRepository extends AbstractRepository
{
    /**
     * The maximum number of items to show in the amdin interface
     */
    const MAX_ITEMS_TO_SHOW_ADMIN = 20;

    /**
     * UrlRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Url::class);
    }

    /**
     * @param Url $url
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Url $url)
    {
        $this->getEntityManager()->remove($url);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Page $page
     *
     * @return mixed
     */
    public function getDefaultUrl(Page $page)
    {
        return $this->getEntityManager()->findOneBy(
            [
                'content' => $page,
                'default' => true,
            ]
        );
    }
}
