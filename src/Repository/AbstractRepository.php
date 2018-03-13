<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

abstract class AbstractRepository extends ServiceEntityRepository
{
    /**
     * Returns the count for entries in the current repository match any
     * provided constraints
     * @param string[] Array of elements and string replacements
     * @return int The number of entities located
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllCount($where = array())
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('count(q.id)')
            ->from($this->getClassName(), 'q');
        if (!empty($where)) {
            $qb->where($where[0]);
            if (isset($where[1])) {
                $qb->setParameters($where[1]);
            }
        }
        return (int) $qb
            ->getQuery()
            ->getSingleScalarResult();
    }
}
