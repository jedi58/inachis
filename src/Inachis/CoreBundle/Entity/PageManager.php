<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Manager class for handling {@link {Page}
 * functions
 */
class PageManager extends AbstractManager
{
    /**
     * @var Page
     */
    protected $page;
    /**
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Page';
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
            array(),
            array(),
            $limit,
            $offset
        );
    }
}
