<?php

namespace Inachis\Component\CoreBundle\Entity;

use Doctrine\ORM\EntityManager;

/**
 * Object for handling User entity
 * @Entity @Table
 */
class User
{
    /**
     * @Id @Column(type="string", unique=true, nullable=false)
     * @GeneratedValue(strategy="UUID")
     * @var string The unique identifier for the {@link User}
     */
    protected $id;
    /**
     * @Column(type="string", length=30, unique=true, nullable=false)
     * @var Username of the user
     */
    protected $username;
    /**
     * @Column(type="string", length=64, nullable=false)
     * @var Password for the user
     */
    protected $password;
    /**
     * @Column(type="string", length=256, nullable=false)
     * @var Email address of the user
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
     * Default constructor for {@link User}. If a password if passed into
     * the constructor it will use {@link setPasswordHash} to store a hashed
     * version of the password instead. This entity should never hold
     * the password in plain-text.
     * @param string $username The username for the {@link User}
     * @param string $password The password for the {@link User}
     * @param string $email The email for the {@link User}
     */
    public function __construct($username = '', $password = '', $email = '')
    {
        $this->setUsername($username);
        $this->setPasswordHash($password);
        $this->setEmail($email);
        $currentTime = new \DateTime('now');
        $this->setCreateDateFromDateTime($currentTime);
        $this->setModDateFromDateTime($currentTime);
        $this->setPasswordModDateFromDateTime($currentTime);
    }
    /**
     * Returns the {@link id} of the {@link User}
     * @return string The ID of the user
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Returns the {@link username} of the {@link User}
     * @return string The username of the user
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * Returns the {@link password} hash for the {@link User}
     * @return string The password hash for the user
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Returns the {@link email} of the {@link User}
     * @return string The email of the user
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Returns the {@link displayName} for the {@link User}
     * @return string The display name for the user
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }
    /**
     * Returns the {@link avatar} for the {@link User}
     * @return string The avatar for the user
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
    /**
     * Returns the {@link isActive} for the {@link User}
     * @return bool Returns check if the user is active
     */
    public function isEnabled()
    {
        return $this->isActive;
    }
    /**
     * Returns the {@link isRemoved} for the {@link User}
     * @return bool Returns check if the user has been "deleted"
     */
    public function hasBeenRemoved()
    {
        return $this->isRemoved;
    }
    /**
     * Returns the {@link createDate} for the {@link User}
     * @return string The creation date for the user
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
    /**
     * Returns the {@link modDate} for the {@link User}
     * @return string The modification for the user
     */
    public function getModDate()
    {
        return $this->modDate;
    }
    /**
     * Returns the {@link passwordModDate} for the {@link User}
     * @return string The password last modification date for the user
     */
    public function getPasswordModDate()
    {
        return $this->passwordModDate;
    }
    /**
     * Sets the value of {@link Id}
     * @param string $value The value to set
     */
    public function setId($value)
    {
        $this->id = $value;
    }
    /**
     * Sets the value of {@link username}
     * @param string $value The value to set
     */
    public function setUsername($value)
    {
        $this->username = $value;
    }
    /**
     * Sets the value of {@link password}
     * @param string $value The value to set
     */
    public function setPassword($value)
    {
        $this->password = $value;
    }
    /**
     * Sets the value of {@link password}
     * @param string $value The value to hash
     * @param int The hashing method to use
     * @param array[mixed] Any hashing algortithm specific options to use
     */
    public function setPasswordHash(
        $value,
        $method = PASSWORD_DEFAULT,
        $options = array()
    ) {
        $this->setPassword(password_hash($value, $method, $options));
    }
    /**
     * Sets the value of {@link email}
     * @param string $value The value to set
     */
    public function setEmail($value)
    {
        $this->email = $value;
    }
    /**
     * Sets the value of {@link displayName}
     * @param string $value The value to set
     */
    public function setDisplayName($value)
    {
        $this->displayName = $value;
    }
    /**
     * Sets the value of {@link avatar}
     * @param string $value The value to set
     */
    public function setAvatar($value)
    {
        $this->avatar = $value;
    }
    /**
     * Sets the value of {@link isActive}
     * @param bool $value The value to set
     */
    public function setActive($value)
    {
        $this->isActive = (bool) $value;
    }
    /**
     * Sets the value of {@link isRemoved}
     * @param bool $value The value to set
     */
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
        //$this->setCreateDate($value->format('Y-m-d H:i:s'));
        $this->createDate = $value;
    }
    /**
     * Sets the value of {@link modDate}
     * @param string $value Specifies the mod date for the {@link User}
     */
    public function setModDateFromString($value)
    {
        $this->modDate = $value;
    }
    /**
     * Sets the {@link modDate} from a DateTime object
     * @param \DateTime $value The date to set
     */
    public function setModDateFromDateTime(\DateTime $value)
    {
        //$this->setModDate($value->format('Y-m-d H:i:s'));
        $this->modDate = $value;
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
        //$this->setPasswordModDate($value->format('Y-m-d H:i:s'));
        $this->passwordModDate = $value;
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
    /**
     * Confirms provided address is generally in the right sort of format
     * to be an email address
     * @return bool The result of testing the email address
     */
    public function validateEmail()
    {
        return (bool) preg_match(
            '/[a-z0-9!#\$%&\'*+\/=?^_`{|}~-]+' .
            '(?:\.[a-z0-9!#\$%&\'*+\/=?^_`{|}~-]+)' .
            '*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+' .
            '[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/',
            $this->email
        );
    }
    /**
     * Checks the password submitted against the stored password hash
     * @param string $password The password to validate
     * @return bool Result of testing the password matches the hash
     */
    public function validatePasswordHash($password)
    {
        return password_verify($password, $this->password);
    }
}
