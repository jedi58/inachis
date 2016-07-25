<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Object for handling pages of a site
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"title", "author_id"})})
 */
class Page
{
    /**
     * @const string Indicates a Page is currently in draft
     */
    const DRAFT = 'draft';
    /**
     * @const string Indicates a Page has been published
     */
    const PUBLISHED = 'published';
    /**
     * @const string Indicates a Page is public
     */
    const VIS_PUBLIC = 'public';
    /**
     * @const string Indicates a Page is private
     */
    const VIS_PRIVATE = 'private';
    /**
     * @const string Indicates a Page is standalone
     */
    const TYPE_PAGE = 'page';
    /**
     * @const string Indicates a Page is a blog post
     */
    const TYPE_POST = 'post';
    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string The title of the {@link Page}
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=255)
     * @var string An optional sub-title for the {@link Page}
     */
    protected $subTitle;
    /**
     * @ORM\Column(type="text")
     * @var string The contents of the {@link Page}
     */
    protected $content;
    /**
     * @ORM\ManyToOne(targetEntity="Inachis\Component\CoreBundle\Entity\User")
     * @var string The UUID of the author for the {@link Page}
     */
    protected $author;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string The featured image for the {@link Page}
     */
    protected $featureImage;
    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string A short excerpt describing the contents of the {@link Page}
     */
    protected $featureSnippet;
    /**
     * @ORM\Column(type="string", length=20)
     * @var string Current status of the {@link Page}, defaults to @{link DRAFT}
     */
    protected $status = self::DRAFT;
    /**
     * @ORM\Column(type="string", length=20)
     * @var string Determining if a @{link Page} is visible to the public
     */
    protected $visibility = self::VIS_PUBLIC;
    /**
     * @ORM\Column(type="datetime")
     * @var string The date the {@link Page} was created
     */
    protected $createDate;
    /**
     * @ORM\Column(type="datetime")
     * @var string The date the {@link Page} was published; a future date
     *             indicates the content is scheduled
     */
    protected $postDate;
    /**
     * @ORM\Column(type="datetime")
     * @var string The date the {@link Page} was last modified
     */
    protected $modDate;
    /**
     * @ORM\Column(type="string", length=50)
     * @var string The timezone for the publication date; defaults to UTC
     */
    protected $timezone = 'UTC';
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string A password to protect the {@link Page} with if required
     */
    protected $password;
    /**
     * @ORM\Column(type="boolean", nullable=false)
     * @var bool Flag determining if the {@link Page} allows comments
     */
    protected $allowComments = false;
    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string The type of page. Default: {@link self::TYPE_POST}
     */
    protected $type = self::TYPE_POST;
    /**
     * @ORM\OneToMany(targetEntity="Inachis\Component\CoreBundle\Entity\Url", mappedBy="content")
     * @ORM\OrderBy({"default" = "DESC"})
     * @var Url[]
     */
    protected $urls;
    /**
     * Default constructor for {@link Page}
     * @param string $title
     * @param string $content
     * @param string $author
     */
    public function __construct($title = '', $content = '', $author = null, $type = self::TYPE_POST)
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setAuthor($author);
        $currentTime = new \DateTime('now');
        $this->setCreateDate($currentTime);
        $this->setPostDate($currentTime);
        $this->setModDate($currentTime);
        $this->type = $type;
        $this->urls = new ArrayCollection();
    }
    /**
     * Returns the value of {@link id}
     * @return string The UUID of the {@link Page}
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Returns the value of {@link title}
     * @return string The title of the {@link Page} - cannot be empty
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Returns the value of {@link subTitle}
     * @return string The Sub-title of the {@link Page}
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }
    /**
     * Returns the value of {@link content}
     * @return string The contents of the {@link Page}
     */
    public function getContent()
    {
        return $this->content;
    }
    /**
     * Returns the value of {@link author}
     * @return string The UUID of the {@link Page} author
     */
    public function getAuthor()
    {
        return $this->author;
    }
    /**
     * Returns the value of {@link featureImage}
     * @return string The UUID or URL of the feature image
     */
    public function getFeatureImage()
    {
        return $this->featureImage;
    }
    /**
     * Returns the value of {@link featureSnippet}
     * @return string The short excerpt to used as the feature
     */
    public function getFeatureSnippet()
    {
        return $this->featureSnippet;
    }
    /**
     * Returns the value of {@link status}
     * @return string The current publishing status of the {@link Page}
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * Returns the value of {@link visibility}
     * @return string The current visibility of the {@link Page}
     */
    public function getVisibility()
    {
        return $this->visibility;
    }
    /**
     * Returns the value of {@link createDate}
     * @return string The creation date of the {@link Page}
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
    /**
     * Returns the value of {@link postDate}
     * @return string The publication date of the {@link Page}
     */
    public function getPostDate()
    {
        return $this->postDate;
    }
    /**
     * Returns the value of {@link modDate}
     * @return string The date the {@link Page} was last modified
     */
    public function getModDate()
    {
        return $this->modDate;
    }
    /**
     * Returns the value of {@link timezone}
     * @return string The timezone for {@link post_date}
     */
    public function getTimezone()
    {
        return $this->timezone;
    }
    /**
     * Returns the value of {@link password}
     * @return string The hash of the password protecting the {@link Page}
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Returns the value of {@link allowComments}
     * @return bool Flag indicating if the {@link Page} allows comments
     */
    public function getAllowComments()
    {
        return $this->allowComments;
    }
    /**
     * Returns the type of the current {@link Page} entity
     * @return string The current type
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Returns an array of URLs assigned to the page. The default URL will
     * always be first.
     * @return Url[] The array of {$link Url} entities for the {@link Page}
     */
    public function getUrls()
    {
        return $this->urls->toArray();
    }
    /**
     * Returns the Url with a specific index within the array
     * @param mixed $key The index of the item to return
     * @return Url The requested {@link Url} entry
     */
    public function getUrl($key)
    {
        if (!isset($this->urls[$key])) {
            throw new \InvalidArgumentException(sprintf('Url `%s` does not exist', $key));
        }
        return $this->urls[$key];
    }
    /**
     * Sets the value of {@link id}
     * @param string $value The UUID of the {@link Page}
     */
    public function setId($value)
    {
        $this->id = $value;
    }
    /**
     * Sets the value of {@link title}
     * @param string $value The title of the {@link Page}
     */
    public function setTitle($value)
    {
        $this->title = $value;
    }
    /**
     * Sets the value of {@link subTitle}
     * @param string $value The optional sub-title of the {@link Page}
     */
    public function setSubTitle($value)
    {
        $this->subTitle = $value;
    }
    /**
     * Sets the value of {@link content}
     * @param string $value The contents of the {@link Page}
     */
    public function setContent($value)
    {
        $this->content = $value;
    }
    /**
     * Sets the value of {@link author}
     * @param string $value The UUID of the {@link Page} author
     */
    public function setAuthor(User $value = null)
    {
        $this->author = $value;
    }
    /**
     * Sets the value of {@link featureImage}
     * @param string $value The UUID or URL to use for the {@link feature_image}
     */
    public function setFeatureImage($value)
    {
        $this->featureImage = $value;
    }
    /**
     * Sets the value of {@link featureSnippet}
     * @param string $value Short excerpt to use with the {@link feature_image}
     */
    public function setFeatureSnippet($value)
    {
        $this->featureSnippet = $value;
    }
    /**
     * Sets the value of {@link status}
     * @param string $value The new publishing status of the {@link Page}
     */
    public function setStatus($value)
    {
        $this->status = $this->isValidStatus($value) ? $value : self::DRAFT;
    }
    /**
     * Sets the value of {@link visibility}
     * @param string $value The visibility of the {@link Page}
     */
    public function setVisibility($value)
    {
        $this->visibility = $this->isValidVisibility($value) ? $value : self::VIS_PRIVATE;
    }
    /**
     * Sets the value of {@link createDate}
     * @param \DateTime $value The date to be set
     */
    public function setCreateDate(\DateTime $value)
    {
        $this->createDate = $value;
    }
    /**
     * Sets the value of {@link postDate}
     * @param \DateTime $value The date to be set
     */
    public function setPostDate(\DateTime $value)
    {
        $this->postDate = $value;
    }
    /**
     * Sets the value of {@link modDate}
     * @param \DateTime $value The date to set
     */
    public function setModDate(\DateTime $value)
    {
        $this->modDate = $value;
    }
    /**
     * Sets the value of {@link timezone}
     * @param string $value The timezone for the post_date
     */
    public function setTimezone($value)
    {
        $this->timezone = $value;
    }
    /**
     * Sets the value of {@link password}
     * @param string $value The password to protect the {@link Page} with
     */
    public function setPassword($value)
    {
        $this->password = $value;
    }
    /**
     * Sets the value of {@link allowComments}
     * @param bool $value Flag specifying if comments allowed on {@link Page}
     */
    public function setAllowComments($value = true)
    {
        $this->allowComments = (bool) $value;
    }
    /**
     * Sets the current type of {@link Page} entity
     * @param string $type The type of page
     * @throws \Exception
     */
    public function setType($type)
    {
        if (!in_array($type, array(self::TYPE_POST, self::TYPE_PAGE))) {
            throw new \Exception(sprintf('`%s` is not a valid page type', $type));
        }
        $this->type = $type;
    }
    /**
     * Adds a {@link Url} to the {@link Page}
     * @param Url $url The new {@link Url} to add to the {@link Page}
     */
    public function addUrl(Url $url)
    {
        $this->urls[] = $url;
    }
    /**
     * Returns the current posts date as a YYYY/mm/dd URL
     * @return string The date part of the post's URL
     */
    public function getPostDateAsLink()
    {
        return $this->postDate->format('Y') .
            '/' . $this->postDate->format('m') .
            '/' . $this->postDate->format('d') .
            '/';
    }
    /**
     * Confirms the status being set to the {@link Page} is valid
     * @param string $value The string to test as being a valid status
     * @return bool Result of testing if string is draft or published
     */
    public function isValidStatus($value)
    {
        return $value === self::DRAFT || $value === self::PUBLISHED;
    }
    /**
     * Confirms the visibility being set to the {@link Page} is valid
     * @param string $value The visibility to test as being valid
     * @return bool Result of testing of the visibility is public or private
     */
    public function isValidVisibility($value)
    {
        return $value === self::VIS_PRIVATE || $value === self::VIS_PUBLIC;
    }
    /**
     * Determines of a provided string is a valid Timezone defined in PHP (>5.2)
     * @param string $timezone The string to test
     * @return bool The result of testing if string is a valid Timezone
     */
    public function isValidTimezone($timezone)
    {
        return in_array($timezone, \DateTimeZone::listIdentifiers());
    }
    /**
     * Determines if current page is scheduled for publishing
     * @return bool Result of testing if {@link post_date} is in the future
     */
    public function isScheduledPage()
    {
        $today = new \DateTime('now', new \DateTimeZone($this->getTimezone()));
        $postDate = new \DateTime(
            $this->getPostDate()->format('Y-m-d H:i:s'),
            new \DateTimeZone($this->getTimezone())
        );
        return $postDate->format('YmdHis') > $today->format('YmdHis');
    }
}
