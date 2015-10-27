<?php

namespace Inachis\Core;

/**
 * Object for handling pages of a site
 * @Entity @Table
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
     * @Id @Column(type="string", unique=true, nullable=false)
     * @GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;
    /**
     * @Column(type="string", length=255, nullable=false)
     * @var string The title of the {@link Page}
     */
    protected $title;
    /**
     * @Column(type="string", length=255)
     * @var string An optional sub-title for the {@link Page}
     */
    protected $sub_title;
    /**
     * @Column(type="text")
     * @var string The contents of the {@link Page}
     */
    protected $content;
    /** 
     * @Column(type="string", length=255, nullable=false)
     * @var string The UUID of the author for the {@link Page}
     */
    protected $author;
    /**
     * @Column(type="string", length=255)
     * @var string The featured image for the {@link Page}
     */
    protected $feature_image;
    /**
     * @Column(type="text")
     * @var string A short excerpt describing the contents of the {@link Page}
     */
    protected $feature_snippet;
    /**
     * @Column(type="string", length=20)
     * @var string Current status of the {@link Page}, defaults to @{link DRAFT}
     */
    protected $status = self::DRAFT;
    /**
     * @Column(type="string", length=20)
     * @var string Determining if a @{link Page} is visible to the public
     */
    protected $visibility = self::VIS_PUBLIC;
    /**
     * @Column(type="DateTime")
     * @var string The date the {@link Page} was published; a future date
     *             indicates the content is scheduled
     */
    protected $post_date;
    /**
     * @Column(type="DateTime")
     * @var string The date the {@link Page} was last modified
     */
    protected $mod_date;
    /**
     * @Column(type="string", length=50)
     * @var string The timezone for the publication date; defaults to UTC
     */
    protected $timezone = 'UTC';
    /**
     * @Column(type="string", length=255)
     * @var string A password to protect the {@link Page} with if required
     */
    protected $password;
    /**
     * @Column(type="boolean", nullable=false)
     * @var bool Flag determining if the {@link Page} allows comments
     */
    protected $allow_comments = false;
    
    public function __construct($title = '', $content = '', $author = '')
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setAuthor($author);
        //$this->setPostDate(date('Y-m-d H:i:s'));
        //$this->setModDate(date('Y-m-d H:i:s'));
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
     * Returns the value of {@link sub_title}
     * @return string The Sub-title of the {@link Page}
     */
    public function getSubTitle()
    {
        return $this->sub_title;
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
     * Returns the value of {@link feature_image}
     * @return string The UUID or URL of the feature image
     */
    public function getFeatureImage()
    {
        return $this->feature_image;
    }
    /**
     * Returns the value of {@link feature_snippet}
     * @return string The short excerpt to used as the feature
     */
    public function getFeatureSnippet()
    {
        return $this->feature_snippet;
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
     * Returns the value of {@link post_date}
     * @return string The publication date of the {@link Page}
     */
    public function getPostDate()
    {
        return $this->post_date;
    }
    /**
     * Returns the value of {@link mod_date}
     * @return string The date the {@link Page} was last modified
     */
    public function getModDate()
    {
        return $this->mod_date;
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
     * Returns the value of {@link allow_comments}
     * @return bool Flag indicating if the {@link Page} allows comments
     */
    public function getAllowComments()
    {
        return $this->allow_comments;
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
     * Sets the value of {@link sub_title}
     * @param string $value The optional sub-title of the {@link Page}
     */
    public function setSubTitle($value)
    {
        $this->sub_title = $value;
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
    public function setAuthor($value)
    {
        $this->author = $value;
    }
    /**
     * Sets the value of {@link feature_image}
     * @param string $value The UUID or URL to use for the {@link feature_image}
     */
    public function setFeatureImage($value)
    {
        $this->feature_image = $value;
    }
    /**
     * Sets the value of {@link feature_snippet}
     * @param string $value Short excerpt to use with the {@link feature_image}
     */
    public function setFeatureSnippet($value)
    {
        $this->feature_snippet = $value;
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
     * Sets the value of {@link post_date}
     * @param string $value The date the post was published
     */
    public function setPostDate($value)
    {
        $this->post_date = $value;
    }
    /**
     * Sets the {@link post_date} from a DateTime object
     * @param \DateTime $value The date to be set
     */
    public function setPostDateFromDateTime(\DateTime $value)
    {
        $this->post_date = $value->format('Y-m-d H:i:s');
    }
    /**
     * Sets the value of {@link mod_date}
     * @param string $value Specifies the mod date for the {@link Page}
     */
    public function setModDate($value)
    {
        $this->mod_date = $value;
    }
    /**
     * Sets the {@link mod_date} from a DateTime object
     * @param \DateTime $value The date to set
     */
    public function setModDateFromDateTime(\DateTime $value)
    {
        $this->mod_date = $value->format('Y-m-d H:i:s');
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
     * Sets the value of {@link allow_comments}
     * @param bool $value Flag specifying if comments allowed on {@link Page}
     */
    public function setAllowComments($value = false)
    {
        $this->allow_comments = (bool) $value;
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
        $post_date = new \DateTime( $this->getPostDate(),
                                    new \DateTimeZone($this->getTimezone()));
        return $post_date->format('YmdHis') > $today->format('YmdHis');
    }
}
