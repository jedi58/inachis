<?php

namespace Inachis\Component\CoreBundle\Entity;

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
