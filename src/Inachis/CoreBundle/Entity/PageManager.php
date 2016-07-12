<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Used to manage interactions with the {@link Page}
 * Entity/Repository
 */
class PageManager extends AbstractManager
{
    /**
     *
     * @return string
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\Page';
    }

    public function create($values = array())
    {
        return $this->hydrate(new Page(), $values);
    }
    
    public function save(Page $page)
    {
        $page->setModDate(new \DateTime('now'));
        $this->em->merge($page);
        $this->em->flush();
    }
    
    public function remove(Page $page)
    {
        $this->em->remove($page);
        $this->em->flush();
    }
}
