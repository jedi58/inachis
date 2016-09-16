<?php

namespace Inachis\Component\CoreBundle\Entity\Security;

use Doctrine\ORM\Mapping as ORM;

/**
 * Object for handling persistent login tokens
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"userId", "activityType"})})
 */
class Activity
{
    /**
     * @const Indicates the user has signed in to the admin interface
     */
    const ACTIVITY_LOGIN = 'login';
    /**
     * @const Indicates the user has signed out of the admin interface
     */
    const ACTIVITY_LOGOUT = 'logout';
    /**
     * @const Indicates the user has signed in to the admin interface via a cookie
     */
    const ACTIVITY_PERSIST = 'persist';
    /**
     * @const Indicates the user has created new content
     */
    const ACTIVITY_CREATE = 'create';
    /**
     * @const Indicates the user has updated content
     */
    const ACTIVITY_UPDATE = 'update';
    /**
     * @const Indicates the user has removed content
     */
    const ACTIVITY_REMOVE = 'remove';
    /**
     * @const Indicates the user/task has published content
     */
    const ACTIVITY_PUBLISH = 'publish';
    /**
     * @const Indicates the user/task has shared content
     */
    const ACTIVITY_SHARED = 'shared';
    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string The id of the {@link User}
     */
    protected $userId;
    /**
     * @ORM\Column(type="string", length=15, name="activityType", nullable=false)
     * @var string The hash of the user's Id and user-agent
     */
    protected $type;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string The hash of the user's token
     */
    protected $detail;
    /**
     * @ORM\Column(type="datetime")
     * @var string The date and time of the action
     */
    protected $timestamp;

    /**
     * Default constructor for {@link Activity}
     * @param null $userId
     * @param null $type
     * @param null $detail
     * @param null $timestamp
     */
    public function __construct($userId = null, $type = null, $detail = null, $timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = new \DateTime();
        }
        $this->setUserId($userId);
        $this->setType($type);
        $this->setDetail($detail);
        $this->setTimestamp($timestamp);
    }
    /**
     * Gets the value of id.
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Sets the value of id.
     * @param string $id the id
     * @return self
     */
    protected function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    /**
     * Gets the value of userId.
     * @return string The id of the {@link User}
     */
    public function getUserId()
    {
        return $this->userId;
    }
    /**
     * Sets the value of userId.
     * @param string The id of the {@link User} $userId the user id
     * @return self
     */
    protected function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }
    /**
     * Gets the value of type.
     * @return string The hash of the user's Id and user-agent
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Sets the value of type.
     * @param string The hash of the user's Id and user-agent $type the type
     * @return self
     */
    protected function setType($type)
    {
        $this->type = $type;
        return $this;
    }
    /**
     * Gets the value of detail.
     * @return string The hash of the user's token
     */
    public function getDetail()
    {
        return $this->detail;
    }
    /**
     * Sets the value of detail.
     * @param string The hash of the user's token $detail the detail
     * @return self
     */
    protected function setDetail($detail)
    {
        $this->detail = $detail;
        return $this;
    }
    /**
     * Gets the value of timestamp.
     * @return string The date and time of the action
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Sets the value of timestamp.
     * @param \DateTime $timestamp
     * @return Activity
     */
    protected function setTimestamp(\DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }
}
