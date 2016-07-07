<?php

namespace Inachis\Component\CoreBundle\Security;

use ParagonIE\Halite\Symmetric\EncryptionKey;
use ParagonIE\Halite\Symmetric\Crypto as Symmetric;
use ParagonIE\Halite\Asymmetric\Crypto as Asymmetric;

/**
 * Object for handling encryption on the site
 */
class Encryption
{
    /**
     * @const string The namespace of the Symmetric crypto class to use
     */
    const SYMMETRIC = 'ParagonIE\Halite\Symmetric\Crypto';
    /**
     * @const string The namespace of the Asymmetric crypto class to use
     */
    const ASYMMETRIC = 'ParagonIE\Halite\Asymmetric\Crypto';
    /**
     * @var EncryptionKey The encryption key to use for encryption and decryption
     */
    protected static $key;
    /**
     * @var bool Flag indicating if encryption/decryption should use raw binary data
     */
    protected static $isRaw = false;
    /**
     * Default constructor for {@link Encryption} depends on an {@link EncryptionKey}
     * being passed in
     * @param string $key The {@link EncryptionKey} to use for encryption/decryption
     */
    public function __construct($key)
    {
        self::$key = new EncryptionKey($key);
    }
    /**
     * Encrypts the provided value
     * @param string $value The string to encrypt
     * @param string $method The encryption method to use (Default: Symmetric)
     * @return string The encrypted string
     * @throws \Exception
     */
    public function encrypt($value, $method = self::SYMMETRIC)
    {
        if (!in_array($method, array(self::SYMMETRIC, self::ASYMMETRIC))) {
            throw new \Exception(sprintf('Encryption method `%s` not recognised', $method));
        }
        return $method::encrypt($value, self::$key, self::$isRaw);
    }
    /**
     * Decrypts the provided value
     * @param string $value The string to decrypt
     * @param string $method The decryption method to use (Default: Symmetric)
     * @return string The decrypted string
     * @throws \Exception
     */
    public function decrypt($value, $method = self::SYMMETRIC)
    {
        if (!in_array($method, array(self::SYMMETRIC, self::ASYMMETRIC))) {
            throw new \Exception(sprintf('Decryption method `%s` not recognised', $method));
        }
        return $method::decrypt($value, self::$key, self::$isRaw);
    }
    /**
     * Sets the encryption method to return raw binary if true,
     * hexadecimal if false
     * @param bool $raw Flag specifying if raw binary should be returned
     */
    public function setRaw($raw)
    {
        self::$isRaw = (bool) $raw;
    }
    /**
     * Returns the value of the {@link isRaw} flag
     * @return bool The value of {@link isRaw}
     */
    public function getRaw()
    {
        return self::$isRaw;
    }
    /**
     * Returns the current {@link EncryptionKey} object
     * @return EncryptionKey The encryption key being used
     */
    public function getKey()
    {
        return self::$key;
    }
}
