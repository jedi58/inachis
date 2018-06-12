<?php

namespace App\Repository;

use App\Entity\Page;
use App\Entity\Url;
use App\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

final class PageRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    /**
     * @param Page $page
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Page $page)
    {
        foreach ($page->getUrls() as $postUrl) {
            $this->getEntityManager()->getRepository(Url::class)->remove($postUrl);
        }
        // @todo are series links automatically removed? assume not
        $this->getEntityManager()->remove($page);
        $this->getEntityManager()->flush();
    }
}
