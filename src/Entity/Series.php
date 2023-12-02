<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeriesRepository")
 */
class Series
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     *
     * @var \Ramsey\Uuid\UuidInterface
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     *
     * @var string
     */
    protected $title;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    protected $subTitle;
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @var string
     */
    protected $url;
    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @var string
     */
    protected $description;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var string
     */
    protected $firstDate;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var string
     */
    protected $lastDate;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Page", inversedBy="series", fetch="EAGER")
     * @ORM\JoinTable(
     *     name="Series_pages",
     *     joinColumns={
     *      @ORM\JoinColumn(name="series_id", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *      @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     *     }
     * )
     * @ORM\OrderBy({"postDate" = "ASC"})
     *
     * @var Collection|Page[] The array of pages in the series
     */
    protected $items = [];
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Image", cascade={"detach"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     *
     * @var Image
     */
    protected $image;
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
     * Series constructor.
     */
    public function __construct()
    {
        $this->items = new ArrayCollection();

        $currentTime = new \DateTime('now');
        $this->setCreateDate($currentTime);
        $this->setModDate($currentTime);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @param mixed $subTitle
     *
     * @return $this
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstDate()
    {
        return $this->firstDate;
    }

    /**
     * @param \DateTime $firstDate
     * @return $this
     */
    public function setFirstDate(\DateTime $firstDate = null)
    {
        $this->firstDate = $firstDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastDate()
    {
        return $this->lastDate;
    }

    /**
     * @param \DateTime $lastDate
     *
     * @return $this
     */
    public function setLastDate(\DateTime $lastDate = null)
    {
        $this->lastDate = $lastDate;

        return $this;
    }

    /**
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param array $items
     *
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @param Page $item
     *
     * @return $this
     */
    public function addItem(Page $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     * @return $this
     */
    public function setImage(Image $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * @param mixed $createDate
     *
     * @return $this
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModDate()
    {
        return $this->modDate;
    }

    /**
     * @param mixed $modDate
     *
     * @return $this
     */
    public function setModDate($modDate)
    {
        $this->modDate = $modDate;

        return $this;
    }
}
