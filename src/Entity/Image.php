<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Object for handling images on a site.
 *
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"title", "filename", "filetype"})})
 */
class Image extends AbstractFile
{
    /**
     * @ORM\Id @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="UUID")
     *
     * @var string The unique identifier for the image
     */
    protected $id;
    /**
     * @const string RegExp for allowed mime-types
     */
    const ALLOWED_TYPES = 'image\/(png|p?jpeg|gif)';
    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $dimensionX;
    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $dimensionY;
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $caption;
    /**
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $altText;

    /**
     * Default constructor for {@link Image}.
     */
    public function __construct()
    {
    }

    public function getDimensionX()
    {
        return $this->dimensionX;
    }

    public function getDimensionY()
    {
        return $this->dimensionY;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function getAltText()
    {
        return $this->altText;
    }

    public function setDimensionX($value)
    {
        $this->dimensionX = (int) $value;
    }

    public function setDimensionY($value)
    {
        $this->dimensionY = (int) $value;
    }

    public function setCaption($value)
    {
        $this->caption = $value;
    }

    public function setAltText($value)
    {
        $this->altText = $value;
    }
}
