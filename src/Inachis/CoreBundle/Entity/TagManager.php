<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Class for handling {@link Tag} specific Entity/Repository interactions
 */
class TagManager extends AbstractManager
{
    /**
     *
     * @return type
     */
    protected function getClass()
    {
        return 'Tag';
    }
    
    public function create()
    {
        return new Tag();
    }
    
    public function save(Tag $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }
    
    public function remove(Tag $tag)
    {
        $this->em->remove($tag);
        $this->em->flush();
    }
}
