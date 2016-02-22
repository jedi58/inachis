<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * Abstract class used to provide common functions to
 * EntityRepository classes
 */
abstract class AbstractManager extends EntityRepository
{
    /**
     * @var EntityManager Used for Repository interactions
     */
    protected $em;
    /**
     * Default consutrctor for AbstractManager
     * @param EntityManager Used for repository interactions
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * Implementations of AbstractManager must implement getClass
     * to indicate the name of the repository
     */
    abstract protected function getClass();
    /**
     * Return the repository
     * @return The repsoitory to return
     */
    protected function getRepository()
    {
        return $this->em->getRepository($this->getClass());
    }
    
    public function getById($id)
    {
        return $this->getRepository()->find($id);
    }
    /**
     *
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function getAll($limit = -1, $offset = -1)
    {
        return $this->getRepository()->findBy(
            array(),
            array(),
            $limit,
            $offset
        );
    }
}
