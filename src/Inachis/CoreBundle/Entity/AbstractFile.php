<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * File entity properties common to different upload types
 * such as {@link Image}
 */
abstract class AbstractFile
{
    /**
     * @Id @Column(type="string", unique=true, nullable=false)
     * @GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;
    /**
     * @Column(type="string", length=255, nullable=false)
     * @var string The title of the {@link Image}
     */
    protected $title;
    /**
     * @Column(type="string")
     * @var string
     */
    protected $description;
    /**
     * @Column(type="string")
     * @var string
     */
    protected $filename;
    /**
     * @Column(type="string")
     * @var string
     */
    protected $filetype;
    /**
     * @Column(type="integer")
     * @var int
     */
    protected $filesize;
    /**
     * @Column(type="string")
     * @var string
     */
    protected $checksum;
    /**
     * @Column(type="datetime")
     * @var string
     */
    protected $createDate;
    /**
     * @Column(type="datetime")
     * @var string
     */
    protected $modDate;
    /**
     * Returns the value of {@link id}
     * @return string The UUID of the record
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Returns the value of {@link title}
     * @return string The title of the record
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * Returns the value of {@link description}
     * @return string The description of the record
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Returns the value of {@link filename}
     * @return string The filename of the record
     */
    public function getFilename()
    {
        return $this->filename;
    }
    /**
     * Returns the value of {@link filetype}
     * @return string The filetype of the record
     */
    public function getFiletype()
    {
        return $this->filetype;
    }
    /**
     * Returns the value of {@link filesize}
     * @return string The filesize of the record
     */
    public function getFilesize()
    {
        return $this->filesize;
    }
    /**
     * Returns the value of {@link checksum}
     * @return string The checksum of the record
     */
    public function getChecksum()
    {
        return $this->checksum;
    }
    /**
     * Returns the value of {@link createDate}
     * @return string The creation date of the file
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }
    /**
     * Returns the value of {@link modDate}
     * @return string The date the file was last modified
     */
    public function getModDate()
    {
        return $this->modDate;
    }
    /**
     * Sets the value of {@link id}
     * @param string $value The id to set
     */
    public function setId($value)
    {
        $this->id = $value;
    }
    /**
     * Sets the value of {@link title}
     * @param string $value The title to set
     */
    public function setTitle($value)
    {
        $this->title = $value;
    }
    /**
     * Sets the value of {@link description}
     * @param string $value The description to set
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }
    /**
     * Sets the value of {@link filename}
     * @param string $value The filename to set
     */
    public function setFilename($value)
    {
        $this->filename = $value;
    }
    /**
     * Sets the value of {@link filetype}
     * @param string $value The filetype to set
     */
    public function setFiletype($value)
    {
        $this->filetype = $value;
    }
    /**
     * Sets the value of {@link filesize}
     * @param string $value The filesize to set
     */
    public function setFilesize($value)
    {
        $this->filesize = (int) $value;
    }
    /**
     * Sets the value of {@link checksum}
     * @param string $value The checksum to set
     */
    public function setChecksum($value)
    {
        $this->checksum = $value;
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
     * @param string $value Specifies the mod date for the {@link Page}
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
     * Verifies the checksum of the file matches the provided one
     * @param string $checksum The checksum to verify against
     * @return bool The result of testing the checksum
     */
    public function verifyChecksum($checksum)
    {
        return $this->checksum === $checksum;
    }
}
