<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Class for handling {@link Tag} specific Entity/Repository interactions
 */
class TagManager extends AbstractManager
{
    /**
     * @var Tag
     */
    protected $tag;
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
    
    public function save()
    {
        if (empty($this->getId)) {
            $this->em->persist($this->tag);
        }
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->tag);
        $this->em->flush();
    }
}
