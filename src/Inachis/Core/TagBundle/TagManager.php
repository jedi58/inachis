<?php

namespace Inachis\Core\TagBundle;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class TagManager extends EntityRepository
{
    /**
     * @var EntityManager Used for Repository interactions
     */
    protected $em;
    /**
     * @var Tag
     */
    protected $tag;
    /**
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * 
     * @return type
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Tag');
    }
    
    public function create()
    {
        return new Tag();
    }
    
    public function save()
    {
        $this->em->persist($this->tag);
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->tag);
        $this->flush();
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
                array(), array(), $limit, $offset
        );
    }
}
