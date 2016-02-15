<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

abstract class AbstractManager extends EntityRepository
{
    /**
     * @var EntityManager Used for Repository interactions
     */
    protected $em;
    /**
     *
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     *
     */
    abstract protected function getClass();
    /**
     *
     */
    protected function getRepository()
    {
        return $this->em->getRepository($this->getClass());
    }
}
