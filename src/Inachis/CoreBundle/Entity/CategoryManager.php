<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Manager class for handling {@link Category}
 * functions
 */
class CategoryManager extends AbstractManager
{
    /**
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Category';
    }
    
    public function create($values = array())
    {
        return $this->hydrate(new Category(), $values);
    }
    
    public function save(Category $category)
    {
        $this->em->persist($category);
        $this->em->flush();
    }
    
    public function remove(Category $category)
    {
        $this->em->remove($category);
        $this->em->flush();
    }

    public function getChildCategories(
        $parentId,
        $limit = -1,
        $offset = -1,
        $orderBy = array('title')
    ) {
        return $this->getAll(
            $limit,
            $offset,
            array('parentId' => $parentId),
            $orderBy
        );
    }
}
