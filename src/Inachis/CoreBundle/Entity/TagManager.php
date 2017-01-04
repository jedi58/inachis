<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Class for handling {@link Tag} specific Entity/Repository interactions
 */
class TagManager extends AbstractManager
{
    /**
     * Returns the full namespace name of the class
     * @return string The class
     */
    protected function getClass()
    {
        return 'Inachis\\Component\\CoreBundle\\Entity\\Tag';
    }
    /**
     * @param array $values
     * @return mixed
     */
    public function create($values = array())
    {
        return $this->hydrate(new Tag(), $values);
    }
    /**
     * @param Tag $tag
     */
    public function save(Tag $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }
    /**
     * @param Tag $tag
     */
    public function remove(Tag $tag)
    {
        $this->em->remove($tag);
        $this->em->flush();
    }
    /**
     * @param $title
     * @return mixed
     */
    public function getByTitle($title)
    {
        return $this->getRepository()->findOneByTitle($title);
    }
}
