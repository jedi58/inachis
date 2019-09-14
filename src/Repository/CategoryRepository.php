<?php

namespace App\Repository;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Returns an array of the root level categories.
     * @return Category[] The array of {@link Category} objects
     */
    public function getRootCategories()
    {
        return $this->getRepository()->createQueryBuilder('q')
            ->where('q.parent is null')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $title
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
    public function findByTitleLike($title)
    {
        return $this->getAll(
            0,
            25,
            [
                'q.title LIKE :title',
                [
                    'title' => '%' . $title . '%',
                ],
            ],
            'q.title'
        );
    }
}
