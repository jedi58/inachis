<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * File entity properties common to different upload types
 * such as {@link Image}.
 */
abstract class AbstractFile
{
    /**
     * @ORM\Id @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string The title of the {@link Image}
     */
    protected $title;
    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $description;
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $filename;
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $filetype;
    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $filesize = 0;
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $checksum;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var string
     */
    protected $createDate;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var string
     */
    protected $modDate;

    /**
     * Returns the value of {@link id}.
     *
     * @return string The UUID of the record
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of {@link title}.
     *
     * @return string The title of the record
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of {@link description}.
     *
     * @return string The description of the record
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of {@link filename}.
     *
     * @return string The filename of the record
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Returns the value of {@link filetype}.
     *
     * @return string The filetype of the record
     */
    public function getFiletype()
    {
        return $this->filetype;
    }

    /**
     * Returns the value of {@link filesize}.
     *
     * @return int The filesize of the record
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Returns the value of {@link checksum}.
     *
     * @return string The checksum of the record
     */
    public function getChecksum()
    {
        return $this->checksum;
    }

    /**
     * Returns the value of {@link createDate}.
     *
     * @return string The creation date of the file
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Returns the value of {@link modDate}.
     *
     * @return string The date the file was last modified
     */
    public function getModDate()
    {
        return $this->modDate;
    }

    /**
     * Sets the value of {@link id}.
     *
     * @param string $value The id to set
     */
    public function setId($value)
    {
        $this->id = $value;
    }

    /**
     * Sets the value of {@link title}.
     *
     * @param string $value The title to set
     */
    public function setTitle($value)
    {
        $this->title = $value;
    }

    /**
     * Sets the value of {@link description}.
     *
     * @param string $value The description to set
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }

    /**
     * Sets the value of {@link filename}.
     *
     * @param string $value The filename to set
     */
    public function setFilename($value)
    {
        $this->filename = $value;
    }

    /**
     * Sets the value of {@link filetype}.
     *
     * @param string $value The filetype to set
     */
    public function setFiletype($value)
    {
        if (!$this->isValidFiletype($value)) {
            throw new FileException(sprintf('Invalid file type %s', $value));
        }
        $this->filetype = $value;
    }

    public function isValidFiletype($value)
    {
        if (defined('static::ALLOWED_TYPES')) {
            return preg_match('/' . static::ALLOWED_TYPES . '/', $value) === 1;
        }
        return true;
    }

    /**
     * Sets the value of {@link filesize}.
     *
     * @param string $value The filesize to set
     */
    public function setFilesize($value)
    {
        if ($value < 0) {
            throw new FileException('File size must be a positive integer');
        }
        $this->filesize = (int) $value;
    }

    /**
     * Sets the value of {@link checksum}.
     *
     * @param string $value The checksum to set
     */
    public function setChecksum($value)
    {
        $this->checksum = $value;
    }

    /**
     * Sets the value of {@link createDate}.
     *
     * @param \DateTime $value The date to be set
     */
    public function setCreateDate(\DateTime $value = null)
    {
        $this->createDate = $value;
    }

    /**
     * Sets the value of {@link modDate}.
     *
     * @param \DateTime $value Specifies the mod date for the {@link Page}
     */
    public function setModDate(\DateTime $value = null)
    {
        $this->modDate = $value;
    }

    /**
     * Verifies the checksum of the file matches the provided one.
     *
     * @param string $checksum The checksum to verify against
     *
     * @return bool The result of testing the checksum
     */
    public function verifyChecksum($checksum)
    {
        return $this->checksum === $checksum;
    }
}
