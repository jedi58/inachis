<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;

/**
 * Object for handling custom URLs that are mapped to content
 * @Entity @Table(indexes={@Index(name="search_idx", columns={"contentType", "contentId", "link"})})
 */
class Url
{
    /**
     * @const The maximum size allowed for SEO-friendly short URLs
     */
    const DEFAULT_URL_SIZE_LIMIT = 255;
    /**
     * @Id @Column(type="string", unique=true, nullable=false)
     * @GeneratedValue(strategy="UUID")
     * @var string The unique identifier for the Url
     */
    protected $id;
    /**
     * @Column(type="string", length=75)
     * @var string The content type the short link refers to
     */
    protected $contentType;
    /**
     * @Column(type="string")
     * @var int The UUID of the content of the type specified by @see $contentType
     */
    protected $contentId;
    /**
     * @Column(type="string", length=512)
     * @var string The SEO-friendly short link
     */
    protected $link;
    /**
     * @Column(type="boolean")
     * @var bool Flag specifying if the URL is the canonical one to use
     */
    protected $default;
    /**
     * @Column(type="datetime", nullable=false)
     * @var string The date the Url was added
     */
    protected $createDate;
    /**
     * @Column(type="datetime", nullable=false)
     * @var string The date the Url was last modified
     */
    protected $modDate;
    /**
     * Default constructor for Inachis\Core\URL entity - by default the
     * URL will be specified as canonical. This can be overridden using
     * {@link Url::setDefault}.
     * @param string $type The content type the URL is for
     * @param int $id The ID of the content record
     * @param string $link The short link for the content
     */
    public function __construct($type = '', $id = '', $link = '')
    {
        $this->setContentType($type);
        $this->setContentId($id);
        $this->setLink($this->urlify($link));
        $this->setDefault(true);
        $this->setCreateDateFromDateTime(new \DateTime('now'));
        $this->setModDateFromDateTime(new \DateTime('now'));
    }
    /**
     * Returns the UUID of the Url
     * @return string The UUID of the URL
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * The Entity name of the content being returned
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
    /**
     * Returns the UUID of the Content being linked to
     * @return string The UUID of the content being linked to
     */
    public function getContentId()
    {
        return $this->contentId;
    }

    public function getLink()
    {
        return $this->link;
    }
    
    public function getDefault()
    {
        return $this->default;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function getModDate()
    {
        return $this->modDate;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function setContentType($value)
    {
        $this->contentType = $value;
    }

    public function setContentId($value)
    {
        $this->contentId = $value;
    }

    public function setLink($value)
    {
        $this->link = $value;
    }
    
    public function setDefault($value)
    {
        $this->default = (bool) $value;
    }
    
    public function setCreateDate($value)
    {
        $this->createDate = $value;
    }
    
    public function setCreateDateFromDateTime(\DateTime $value)
    {
        $this->createDate = $value->format('Y-m-d H:i:s');
    }
    
    public function setModDate($value)
    {
        $this->modDate = $value;
    }
    /**
     * Sets the mod date to the date/time specified
     * @param \DateTime $value
     */
    public function setModDateFromDateTime(\DateTime $value)
    {
        $this->modDate = $value->format('Y-m-d H:i:s');
    }
    /**
     * Sets the mod date for the {@link Url} to the current date
     */
    public function setModDateToNow()
    {
        $this->setModDateFromDateTime(new \DateTime('now'));
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
}
