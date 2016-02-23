<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Used to manage interactions with the {@link Page}
 * Entity/Repository
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
        if (empty($this->getId)) {
            $this->em->persist($this->page);
        }
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->page);
        $this->em->flush();
    }
}
