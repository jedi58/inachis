<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Object for handling images on a site
 * @Entity @Table(indexes={@Index(name="search_idx", columns={"title", "filename", "filetype"})})
 */
class Image extends AbstractFile
{
    /**
     * @const string RegExp for allowed mime-types
     */
    const ALLOWED_TYPES = 'image\/(png|p?jpeg|gif)';
    /**
     * @Column(type="integer")
     * @var int
     */
    protected $dimensionX;
    /**
     * @Column(type="integer")
     * @var int
     */
    protected $dimensionY;
    /**
     * @Column(type="string")
     * @var string
     */
    protected $caption;
    /**
     * @Column(type="string")
     * @var string
     */
    protected $altText;
    /**
     * Default constructor for {@link Image}
     */
    public function __construct()
    {
    }
    /**
     *
     */
    public function getDimensionX()
    {
        return $this->dimensionX;
    }
    /**
     *
     */
    public function getDimensionY()
    {
        return $this->dimensionY;
    }
    /**
     *
     */
    public function getCaption()
    {
        return $this->caption;
    }
    /**
     *
     */
    public function getAltText()
    {
        return $this->altText;
    }
    /**
     *
     */
    public function setDimensionX($value)
    {
        $this->dimensionX = (int) $value;
    }
    /**
     *
     */
    public function setDimensionY($value)
    {
        $this->dimensionY = (int) $value;
    }
    /**
     *
     */
    public function setCaption($value)
    {
        $this->caption = $value;
    }
    /**
     *
     */
    public function setAltText($value)
    {
        $this->altText = $value;
    }
}
