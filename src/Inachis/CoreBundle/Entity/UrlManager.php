<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Manager class for handling {@link Url} interactions
 */
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
        if (empty($this->getId)) {
            $this->em->persist($this->url);
        }
        $this->em->flush();
    }
    
    public function remove()
    {
        $this->em->remove($this->url);
        $this->em->flush();
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
}
