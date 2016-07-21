<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Used to manage interactions with the {@link Page}
 * Entity/Repository
 */
class PageManager extends AbstractManager
{
    /**
     * Returns the full namespaced name of the class
     * @return string The class
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\Page';
    }
    /**
     * Returns a new instance of {@link Page} optionally hydrated
     * with given values
     * @param mixed[] The array of values to assign to the entity
     * @return Page A new instance of {@link Page}
     */
    public function create($values = array())
    {
        return $this->hydrate(new Page(), $values);
    }
    /**
     * Saves the current entity to the repository. If this is a new
     * entity it will be persisted, otherwise it will be merged. For this
     * to be saved `flush()` must be called on the entity manager elsewhere
     * @param Page $page The page to save
     */
    public function save(Page $page)
    {
        $page->setModDate(new \DateTime('now'));
        if (!empty($page->getId())) {
            $this->em->merge($page);
        } else {
            $this->em->persist($page);
        }
    }
    /**
     * Removes the current entity from the repository
     * @param Page $page The page to remove
     */    
    public function remove(Page $page)
    {
        $this->em->remove($page);
        $this->em->flush();
    }
}
