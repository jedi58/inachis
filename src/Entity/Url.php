<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Object for handling custom URLs that are mapped to content
 * @ORM\Entity(repositoryClass="App\Repository\UrlRepository")
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"linkCanonical"})})
 */
class Url
{
    /**
     * @const The maximum size allowed for SEO-friendly short URLs
     */
    const DEFAULT_URL_SIZE_LIMIT = 255;
    /**
     * @ORM\Id @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string The unique identifier for the Url
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Page", inversedBy="urls", fetch="EAGER")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     * @var int The UUID of the content of the type specified by @see $contentType
     */
    protected $content;
    /**
     * @ORM\Column(type="string", length=512)
     * @var string The SEO-friendly short link
     */
    protected $link;
    /**
     * @ORM\Column(name="linkCanonical",type="string", length=255, unique=true)
     * @var string The canonical hash for the link
     */
    protected $linkCanonical;
    /**
     * @ORM\Column(type="boolean", name="defaultLink")
     * @var bool Flag specifying if the URL is the canonical one to use
     */
    protected $default;
    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var string The date the Url was added
     */
    protected $createDate;
    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @var string The date the Url was last modified
     */
    protected $modDate;
    /**
     * Default constructor for entity - by default the
     * URL will be specified as canonical. This can be overridden using
     * {@link Url::setDefault}.
     * @param Page $content The {@link Page} object the link is for
     * @param string $link The short link for the content
     */
    public function __construct(Page $content, $link = '')
    {
        $this->setContent($content);
        $this->setDefault(true);
        $this->setCreateDate(new \DateTime('now'));
        $this->setModDate(new \DateTime('now'));
        $this->associateContent();
    }
    /**
     * Returns the UUID of the Url
     * @return string The UUID of the URL
     */
    public function getId()
    {
        return $this->id;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getContent()
    {
        return $this->content;
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

    public function setLink($value)
    {
        $this->link = $value;
        $this->linkCanonical = md5($value);
    }

    public function setContent($value)
    {
        $this->content = $value;
    }

    public function setDefault($value)
    {
        $this->default = (bool) $value;
    }

    public function setCreateDate(\DateTime $value)
    {
        $this->createDate = $value;
    }

    public function setModDate(\DateTime $value)
    {
        $this->modDate = $value;
    }
    /**
     * Sets the mod date for the {@link Url} to the current date
     */
    public function setModDateToNow()
    {
        $this->setModDate(new \DateTime('now'));
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

    public function associateContent()
    {
        $this->content->addUrl($this);
    }
}
