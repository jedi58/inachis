<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class UrlManager extends EntityRepository
{
    /**
     * @var EntityManager Used for Repository interactions
     */
    protected $em;
    /**
     * @var Url 
     */
    protected $url;
    /**
     * 
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    /**
     * 
     * @return type
     */
    protected function getRepository()
    {
        return $this->em->getRepository('Url');
    }
    
    public function create()
    {
        return new Url();
    }
    
    public function save()
    {
        $this->em->persist($this->url);
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->url);
        $this->flush();
    }
    
    public function getById($id)
    {
        return $this->getRepository()->find($id);
    }
    /**
     * 
     * @param type $content_type
     * @param type $content_id
     * @return type
     */
    public function getAllForContentTypeAndId($content_type, $content_id)
    {
        return $this->getRepository()->findBy(array(
            'content_type' => $content_type,
            'content_id' => $content_id
        ));
    }
    /**
     * Fetches the default URL for the specified content type and UUID
     * @param string $content_type The type of content to return
     * @param string $content_id The UUID of the content to return
     */
    public function getDefaultUrlByContentTypeAndId($content_type, $content_id)
    {
        return $this->getRepository()->findOneBy(array(
            'content_type' => $content_type,
            'content_id' => $content_id,
            'default' => true
        ));
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
                array(), array(), $limit, $offset
        );
    }
}
