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
        $this->em->persist($this->tag);
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->tag);
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
