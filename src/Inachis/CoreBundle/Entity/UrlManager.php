<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Manager class for handling {@link Url} interactions
 */
class UrlManager extends AbstractManager
{
    /**
     * Returns the name of the repository
     * @return string The name of the repository
     */
    protected function getClass()
    {
        return 'Url';
    }
    /**
     * Creates and returns a new instance of {@link Url}
     * @return Url The new entity
     */
    public function create($values = array())
    {
        return $this->hydrate(new Url(), $values);
    }
    /**
     * Saves the current entity (assuming it is attached) and sets
     * it's last modified date to the present
     * @param Url $url The {@link Url} object to save
     */
    public function save(Url $url)
    {
        $url->setModDateFromDateTime(new \DateTime('now'));
        $this->em->persist($url);
        $this->em->flush();
    }
    /**
     * Removes the current entity (assuming it is attached)
     * @param Url $url The {@link Url} object to remove
     */
    public function remove(Url $url)
    {
        $this->em->remove($url);
        $this->em->flush();
    }
    /**
     * Returns all Url entities for a given content type and id
     * @param string $contentType The content type to get the Url(s) for
     * @param string $contentId The UUID of the content to get the Url(s) for
     * @return array[Url] The array of {@link Url}s to return
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
     * @param string $contentType The content type to get the Url(s) for
     * @param string $contentId The UUID of the content to get the Url(s) for
     * @return array[Url] The array of {@link Url}s to return
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
