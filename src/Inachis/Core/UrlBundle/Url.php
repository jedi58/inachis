<?php

namespace Inachis\Core;

/**
 * Object for handling custom URLs that are mapped to content
 * @Entity @Table
 */
class Url
{
    /** @Id @Column(type="integer", length=32, unique=true, nullable=false) @GeneratedValue */
    protected $id;

    /** @Column(type="string", length=75) */
    protected $content_type;

    /** @Column(type="integer", length=32) */
    protected $content_id;

    /** @Column(type="string", length=512) */
    protected $link;
    
    const DEFAULT_URL_SIZE_LIMIT = 255;
    
    /**
     * Default constructor for Inachis\Core\URL entity
     * @param string $type The content type the URL is for
     * @param int $id The ID of the content record
     * @param string $link The short link for the content
     * @param bool $convert_link Flag specifying if $link should be converted
     */
    public function __construct(
        $type = '',
        $id = '',
        $link = '',
        $convert_link = false
    ) {
        $this->__set('content_type', $type);
        $this->__set('content_id', $id);
        $this->__set('link', $convert_link ? $this->urlify($link) : $link);
    }
    
    /**
     * D'tor for the class
     */
    public function __destruct()
    {
        unset($this->id);
        unset($this->content_type);
        unset($this->content_id);
        unset($this->link);
    }
    
    /**
     * Returns the short URL for the current \Inachis\Core\Url object
     * @return string The short URL for the current object
     */
    public function __toString()
    {
        return $this->link;
    }

    /**
     * Returns the value of the specified property
     * @param string $var The name of the property to return the value for
     * @return mixed The contents of the requested property
     */
    public function __get($var)
    {
        switch ($var) {
            case 'id':
                return $this->getId();
            case 'content_type':
                return $this->getContentType();
            case 'content_id':
                return $this->getContentId();
            case 'link':
                return $this->getLink();
            default:
                return parent::__get($var);
        }
    }

    public function getId()
    {
        return (int) $this->id;
    }

    public function getContentType()
    {
        return $this->content_type;
    }

    public function getContentId()
    {
        return (int) $this->content_id;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function __set($var, $value)
    {
        switch ($var) {
            case 'id':
                $this->setId($value);
                break;
            case 'content_type':
                $this->setContentType($value);
                break;
            case 'content_id':
                $this->setContentId($value);
                break;
            case 'link':
                $this->setLink($value);
                break;
            default:
                parent::__set($var, $value);
        }
    }

    public function setId($value)
    {
        $this->id = (int) $value;
    }

    public function setContentType($value)
    {
        $this->content_type = $value;
    }

    public function setContentId($value)
    {
        $this->content_id = (int) $value;
    }

    public function setLink($value)
    {
        $this->link = $value;
    }
    
    /**
     * Test if the current link is a valid SEO-friendly URL
     * @return bool The result of validating if the SEO friendly short URL
     *              contains only alphanumeric values and hyphens
     */
    public function validateURL()
    {
        return preg_match('/^[a-z0-9\-]+$/i', $this->link);
    }
    
    /**
     * Turns a given string into an SEO-friendly URL
     * @param string $title The string to turn into an SEO friendly short URL
     * @param int $limit The maximum number of characters to allow; the default
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
}
