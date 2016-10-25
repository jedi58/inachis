<?php

namespace Inachis\Component\CoreBundle\Entity\Security;

use Doctrine\ORM\Mapping as ORM;

/**
 * Object for handling persistent login tokens
 * @ORM\Entity
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"userId", "userHash"})})
 */
class PersistentLogin
{
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
     * @ORM\Column(type="string", length=512, nullable=false)
     * @var string The hash of the user's Id and user-agent
     */
    protected $userHash;
    /**
     * @ORM\Column(type="string", length=512, nullable=false)
     * @var string The hash of the user's token
     */
    protected $tokenHash;
    /**
     * @ORM\Column(type="datetime")
     * @var string The expiry date of the current token
     */
    protected $expires;
    /**
     * Default constructor for {@link PersistentLogin}
     * @param int $userId The ID of the user the token is for
     * @param string $userHash The hashed userId and user-agent
     * @param string $tokenHash The hashed token
     * @param \DateTime $expires The expiry date for the token
     */
    public function __construct($userId = -1, $userHash = null, $tokenHash = null, $expires = null)
    {
        $this->setUserId($userId);
        $this->setUserHash($userHash);
        $this->setTokenHash($tokenHash);
        $this->setExpires($expires);
    }
    /**
     * Gets the value of id.
     * @return int The ID of the {@link PersistentLogin}
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Sets the value of id.
     * @param string $id The UUID of the {@link PersistentLogin}
     * @return PersistentLogin Returns itself
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets the value of userId.
     * @return string The UUID of the {@link User}
     */
    public function getUserId()
    {
        return $this->userId;
    }
    /**
     * Sets the value of userId.
     * @param string $userId The UUID of the {@link User} the token is for
     * @return PersistentLogin Returns itself
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }
    /**
     * Gets the value of userHash.
     * @return string The hashed userId and user-agent
     */
    public function getUserHash()
    {
        return $this->userHash;
    }
    /**
     * Sets the value of userHash.
     * @param string $hash The hashed userId and user-agent
     * @return PersistentLogin Returns itself
     */
    public function setUserHash($hash)
    {
        $this->userHash = $hash;
        return $this;
    }
    /**
     * Gets the value of hash.
     * @return string The hashed token
     */
    public function getTokenHash()
    {
        return $this->tokenHash;
    }
    /**
     * Sets the value of hash.
     * @param string $hash The hashed token
     * @return PersistentLogin Returns itself
     */
    public function setTokenHash($hash)
    {
        if ($hash !== null && mb_strlen($hash) < 8) {
            throw new \InvalidArgumentException('hash must be at least 8 characters in length and should be random');
        }
        $this->tokenHash = $hash;
        return $this;
    }
    /**
     * Gets the value of expires.
     * @return \DateTime The date and time the token expires
     */
    public function getExpires()
    {
        return $this->expires;
    }
    /**
     * Sets the value of expires.
     * @param \DateTime $expires Sets the expiry date for the token
     * @return PersistentLogin|null Returns itself
     */
    public function setExpires($expires)
    {
        if (!$expires instanceof \DateTime) {
            return null;
        }
        $this->expires = $expires;
        return $this;
    }

    /**
     * Checks if the tokenHash passed in matches the one returned from the repository
     * @param $hash
     * @return bool The result of testing the hash
     */
    public function isTokenHashValid($hash)
    {
        return $this->expires > new \DateTime() && hash_equals($this->tokenHash, $hash);
    }
}
