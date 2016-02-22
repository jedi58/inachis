<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Manager class for handling {@link Category}
 * functions
 */
class CategoryManager extends AbstractManager
{
    /**
     * @var Category
     */
    protected $category;
    /**
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Category';
    }
    
    public function create()
    {
        return new Category();
    }
    
    public function save()
    {
        $this->em->persist($this->category);
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->category);
        $this->em->flush();
    }
}
