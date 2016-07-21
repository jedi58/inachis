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
        return 'Inachis\\Component\\CoreBundle\\Entity\\Url';
    }
    /**
     * Creates and returns a new instance of {@link Url}
     * @return Url The new entity
     */
    public function create($values = array())
    {
        return $this->hydrate(new Url($values['content']), $values);
    }
    /**
     * Saves the current entity (assuming it is attached) and sets
     * it's last modified date to the present
     * @param Url $url The {@link Url} object to save
     */
    public function save(Url $url)
    {
        $url->setModDate(new \DateTime('now'));
        $this->em->persist($url);
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
     * Turns a given string into an SEO-friendly URL
     * @param string $title The string to turn into an SEO friendly short URL
     * @param int    $limit The maximum number of characters to allow;
     *                   the default is defined by URL::DEFAULT_URL_SIZE_LIMIT
     *                   is defined by URL::DEFAULT_URL_SIZE_LIMIT
     * @return string The generated SEO-friendly URL
     */
    public function urlify($title, $limit = URL::DEFAULT_URL_SIZE_LIMIT)
    {
        $title = preg_replace(
            array(
                '/[\_\s]/',
                '/[^a-z0-9\-]/i'
            ),
            array(
                '-',
                ''
            ),
            mb_strtolower($title)
        );
        if (mb_strlen($title) > $limit) {
            $title = mb_substr($title, 0, $limit);
        }
        return $title;
    }
    /**
     * Returns a string containing a "short URL" from the given URI
     * @param string $uri The URL to parse and obtain the short URL for
     * @return string
     */
    public function fromUri($uri)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        if (substr($uri, -1) == '/') {
            $uri = substr($uri, 0, -1);
        }
        $uri = explode('/', $uri);
        return end($uri);
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
    /**
     * Returns a Url entity for the given URI. If the admin part of the URL is
     * present, this is stripped along with parameters and targets
     * @param string $url The URL to get the {@link Url} entity for
     * @return array[Url] The array of {@link Url}s to return
     */
    public function getByUrl($url)
    {
        $url = parse_url(
            preg_replace('/^\/inadmin/', '', $url)
        );
        return $this->getRepository()->findOneBy(
            array(
                'link' => $url['path']
            )
        );
    }
}
