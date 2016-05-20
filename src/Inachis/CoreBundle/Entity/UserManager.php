<?php

namespace Inachis\Component\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\AbstractManager;
use Doctrine\ORM\QueryBuilder;

class UserManager extends AbstractManager
{
    /**
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\User';
    }
    
    public function create($values = array())
    {
        return $this->hydrate(new User(), $values);
    }
    
    public function save(User $user)
    {
        $user->setModDate(new \DateTime('now'));
        $this->em->persist($user);
        $this->em->flush();
    }
    
    public function remove(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function getByUsername($username)
    {
        return $this->getRepository()->findOneBy(array('username' => $username));
    }
    
    public function qbByUsernameAndEmail($usernameOrEmail)
    {
        $qb = $this->getRepository()->createQueryBuilder('u')
            ->where($qb->expr()->like('username', ':username'))
            ->whereOr($qb->expr()->like('email', ':username'))
            ->setParameter('username', $usernameOrEmail);
        return $qb;
    }

    public function getByUsernameAndEmail($usernameOrEmail)
    {
        return $this->qbByUsernameAndEmail($usernameOrEmail)
            ->getQuery()
            ->getResult();
    }

    public function getUserCount()
    {
        return (int) $this->getRepository()->createQueryBuilder('u')
            ->select('count(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
