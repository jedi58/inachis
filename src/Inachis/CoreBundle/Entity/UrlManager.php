<?php

namespace Inachis\Component\CoreBundle\Entity;

class UrlManager extends AbstractManager
{
    /**
     * @var Url
     */
    protected $url;
    /**
     *
     * @return type
     */
    protected function getClass()
    {
        return 'Url';
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
     * @param type $contentType
     * @param type $contentId
     * @return type
     */
    public function getAllForContentTypeAndId($contentType, $contentId)
    {
        return $this->getRepository()->findBy(
            array(
            'contentType' => $contentType,
            'contentId' => $contentId
            )
        );
    }
    /**
     * Fetches the default URL for the specified content type and UUID
     * @param string $contentType The type of content to return
     * @param string $contentId   The UUID of the content to return
     */
    public function getDefaultUrlByContentTypeAndId($contentType, $contentId)
    {
        return $this->getRepository()->findOneBy(
            array(
            'contentType' => $contentType,
            'contentId' => $contentId,
            'default' => true
            )
        );
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