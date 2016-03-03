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
     *
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     *
     */
    public function getTitle()
    {
        return $this->title;
    }
    /**
     *
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     *
     */
    public function getFilename()
    {
        return $this->filename;
    }
    /**
     *
     */
    public function getFiletype()
    {
        return $this->filetype;
    }
    /**
     *
     */
    public function getFilesize()
    {
        return $this->filesize;
    }
    /**
     *
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
     *
     */
    public function setId($value)
    {
        $this->id = $value;
    }
    /**
     *
     */
    public function setTitle($value)
    {
        $this->title = $value;
    }
    /**
     *
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }
    /**
     *
     */
    public function setFilename($value)
    {
        $this->filename = $value;
    }
    /**
     *
     */
    public function setFiletype($value)
    {
        $this->filetype = $value;
    }
    /**
     *
     */
    public function setFilesize($value)
    {
        $this->filesize = (int) $value;
    }
    /**
     *
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
     *
     */
    public function verifyChecksum($checksum)
    {
        return $this->checksum === $checksum;
    }
}
