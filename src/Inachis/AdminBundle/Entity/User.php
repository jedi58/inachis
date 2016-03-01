<?php

namespace Inachis\Component\AdminBundle\Entity;

use Doctrine\ORM\EntityManager;

/**
 * Object for handling User entity
 * @Entity @Table
 */
class User
{
    /**
     * @Id @Column(type="integer", unique=true, nullable=false)
     * @GeneratedValue(strategy="AUTO")
     * @var string The unique identifier for the {@link User}
     */
    protected $id;
    /**
     * @Column(type="string", length=30, unique=true, nullable=false)
     * @var Username of the administrator
     */
    protected $username;
    /**
     * @Column(type="string", length=64, nullable=false)
     * @var Password for the administrator
     */
    protected $password;
    /**
     * @Column(type="string", length=256, nullable=false)
     * @var Email address of the administrator
     */
    protected $email;
    /**
     * @Column(type="string", length=128)
     * @var The display name for the user
     */
    protected $displayName;
    /**
     * @OneToOne(targetEntity="Inachis\Component\CoreBundle\Entity\Image")
     * @JoinColumn(name="avatar", referencedColumnName="id")
     * @var string An image to use for the {@link User}
     */
    protected $avatar;
    /**
     * @Column(type="boolean")
     * @var Flag indicating if the {@link User} can sign in
     */
    protected $isActive = true;
    /**
     * @Column(type="boolean")
     * @var Flag indicating if the {@link User} has been "deleted"
     */
    protected $isRemoved = false;
    /**
     * @Column(type="datetime")
     * @var string The date the {@link User} was added
     */
    protected $createDate;
    /**
     * @Column(type="datetime")
     * @var string The date the {@link User} was last modified
     */
    protected $modDate;
    /**
     * @Column(type="datetime")
     * @var string The date the password was last modified
     */
    protected $passwordModDate;
    /**
     * @Column(type="string", length=64)
     * @var string The salt to use with password hashing
     */
    protected $salt;

    public function __construct($username = '', $password = '', $email = '')
    {
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setEmail($email);
        $currentTime = new \DateTime('now');
        $this->setCreateDateFromDateTime($currentTime);
        $this->setModDateFromDateTime($currentTime);
        $this->setPasswordModDateFromDateTime($currentTime);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDisplayName()
    {
        return $this->displayName;
    }
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    public function hasBeenRemoved()
    {
        return $this->isRemoved;
    }

    public function getCreateDate()
    {
        return $this->createDate;
    }

    public function getModDate()
    {
        return $this->modDate;
    }

    public function getPasswordModDate()
    {
        return $this->passwordModDate;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setId($value)
    {
        $this->id = $value;
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function setPassword($value)
    {
        $this->password = $value;
    }

    public function setEmail($value)
    {
        $this->email = $value;
    }

    public function setDisplayName($value)
    {
        $this->displayName = $value;
    }

    public function setAvatar($value)
    {
        $this->avatar = $value;
    }

    public function setActive($value)
    {
        $this->isActive = (bool) $value;
    }

    public function setRemoved($value)
    {
        $this->isRemoved = (bool) $value;
    }
    /**
     * Sets the value of {@link createDate}
     * @param string $value The date the post was created
     */
    public function setCreateDate($value)
    {
        $this->createDate = $value;
    }
    /**
     * Sets the {@link createDate} from a DateTime object
     * @param \DateTime $value The date to be set
     */
    public function setCreateDateFromDateTime(\DateTime $value)
    {
        $this->setCreateDate($value->format('Y-m-d H:i:s'));
    }
    /**
     * Sets the value of {@link modDate}
     * @param string $value Specifies the mod date for the {@link User}
     */
    public function setModDate($value)
    {
        $this->modDate = $value;
    }
    /**
     * Sets the {@link modDate} from a DateTime object
     * @param \DateTime $value The date to set
     */
    public function setModDateFromDateTime(\DateTime $value)
    {
        $this->setModDate($value->format('Y-m-d H:i:s'));
    }
    /**
     * Sets the value of {@link modDate}
     * @param string $value Specifies the mod date for the {@link User}
     */
    public function setPasswordModDate($value)
    {
        $this->passwordModDate = $value;
    }
    /**
     * Sets the {@link passwordModDate} from a DateTime object
     * @param \DateTime $value The date to set
     */
    public function setPasswordModDateFromDateTime(\DateTime $value)
    {
        $this->setPasswordModDate($value->format('Y-m-d H:i:s'));
    }
    /**
     * Sets the value of {@link salt} - the salt itself should be
     * generated external to this function with sufficent entropy
     * @param string $value The salt to set
     */
    public function setSalt($value)
    {
        $this->salt = $value;
    }
    /**
     * Removes the credentials for the current {@link User} along
     * with personal information other than "displayName"
     */
    public function erase()
    {
        $this->setUsername('');
        $this->setPassword('');
        $this->setEmail('');
        $this->setAvatar('');
        $this->setActive(false);
        $this->setRemoved(true);
    }
    /**
     * Determines if the password has expired by adding {@link expiryDays}
     * to the {@link passwordMoDate} and comparing it to the current time.
     * This function can also be used with a notification period to determine
     * if the user should be alerted
     * @param int $expiryDays The number of days the password expires after
     * @return bool The result of testing the {@link passwordModDate}
     */
    public function hasCredentialsExpired($expiryDays)
    {
        return time() >= strtotime(
            '+' . (int) $expiryDays . ' days',
            strtotime($this->getPasswordModDate())
        );
    }
}
