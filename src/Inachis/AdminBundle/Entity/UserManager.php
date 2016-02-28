<?php

namespace Inachis\Component\AdminBundle\Entity;

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
        return 'User';
    }
    
    public function create($values = array())
    {
        return $this->hydrate(new User(), $values);
    }
    
    public function save(User $user)
    {
        $user->setModDateFromDateTime(new \DateTime('now'));
        $this->em->persist($page);
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
}