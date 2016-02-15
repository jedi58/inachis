<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class PageManager extends EntityRepository
{
    /**
     * @var EntityManager Used for Repository interactions
     */
    protected $em;
    /**
     * @var Page 
     */
    protected $page;
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
        return $this->em->getRepository('Page');
    }
    
    public function create()
    {
        return new Page();
    }
    
    public function save()
    {
        $this->em->persist($this->page);
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->page);
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
