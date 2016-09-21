<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Manager class for handling {@link Category}
 * functions
 */
class CategoryManager extends AbstractManager
{
    /**
     * Returns the full namespace of the current entity
     * @return string The namespace of the entity
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\Category';
    }
    /**
     * Creates a new instance of {@link Category} and hydrates the object with any provided values
     * @param array $values Values to hydrate the object with
     * @return Category The newly created category
     */
    public function create($values = array())
    {
        return $this->hydrate(new Category(), $values);
    }
    /**
     * Saves the provided Category object to the repository
     * @param Category $category The category to save
     */
    public function save(Category $category)
    {
        $this->em->persist($category);
        $this->em->flush();
    }
    /**
     * Removes the provided Category from the repository
     * @param Category $category The category entity to remove
     */
    public function remove(Category $category)
    {
        $this->em->remove($category);
        $this->em->flush();
    }
    /**
     * Returns an array of the root level categories
     * @return Category[] The array of {@link Category} objects
     */
    public function getRootCategories()
    {
        return $this->getRepository()->createQueryBuilder('q')
            ->where('q.parent is null')
            ->getQuery()
            ->getResult();
    }
}
