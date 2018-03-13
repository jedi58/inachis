<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Object for handling categories on a site
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"title"})})
 */
class Category
{
    /**
     * @ORM\Id @ORM\Column(type="string", unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string The name of the category
     */
    protected $title;
    /**
     * @ORM\Column(type="text")
     * @var string Description of the category
     */
    protected $description;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string The UUID of the image, or the image path
     */
    protected $image;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string The UUID of the image, or the image path
     */
    protected $icon;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="children")
     * @var Category The parent category, if self is not a top-level category
     */
    protected $parent;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parent")
     * @ORM\OrderBy({"title" = "ASC"})
     * @var ArrayCollection|Category[] The array of child categories if applicable
     */
    protected $children;

    /**
     * Default constructor for {@link Category}
     * @param string $title The title of the category
     * @param string $description The description for the category
     */
    public function __construct($title = '', $description = '')
    {
        $this->setTitle($title);
        $this->setDescription($description);
        $this->children = new ArrayCollection();
    }

    /**
     * Returns the value of {@link id}
     * @return string The UUID of the {@link Category}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of {@link title}
     * @return string The title of the {@link Category} - cannot be empty
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the value of {@link description}
     * @return string The description of the {@link Category}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of {@link image}
     * @return string The image for {@link Category}
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Returns the value of {@link icon}
     * @return string The "icon" for the {@link Category}
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Returns the value of {@link parent}
     * @return Category The parent {@link Category} if applicable
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns all child categories for the current {@link Category}
     * @return ArrayCollection|Category[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Sets the value of {@link id}
     * @param string $value The UUID of the {@link Category}
     * @return Category
     */
    public function setId($value)
    {
        $this->id = $value;
        return $this;
    }

    /**
     * Sets the value of {@link title}
     * @param string $value The title of the {@link Category}
     * @return Category
     */
    public function setTitle($value)
    {
        $this->title = $value;
        return $this;
    }

    /**
     * Sets the value of {@link description}
     * @param string $value The description of the {@link Category}
     * @return Category
     */
    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

    /**
     * Sets the value of {@link image}
     * @param string $value The UUID or URL of the image for {@link Category}
     * @return Category
     */
    public function setImage($value)
    {
        $this->image = $value;
        return $this;
    }

    /**
     * Sets the value of {@link icon}
     * @param string $value The UUID or URL of the image for {@link Category}
     * @return Category
     */
    public function setIcon($value)
    {
        $this->icon = $value;
        return $this;
    }

    /**
     * Sets the value of {@link parent}
     * @param null|Category $parent The parent of the current category
     */
    public function setParent(Category $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * Adds a child category to the current {@link Category}
     * @param Category $category The {@link Category} to add
     */
    public function addChild(Category $category)
    {
        $this->children[] = $category;
    }

    /**
     * Returns the result of testing if current category is a root category
     * @return bool Result of testing if {@link Category} is a root category
     */
    public function isRootCategory()
    {
        return empty($this->getParent());
    }

    /**
     * Returns the result of testing if the current category is a child category
     * @return bool Result of testing if {@link Category} is a child category
     */
    public function isChildCategory()
    {
        return !empty($this->getParent());
    }

    /**
     * Returns the result of testing if the category has an image to use
     * @return bool Result of testing if {@link image} is empty
     */
    public function hasImage()
    {
        return !empty($this->getImage());
    }

    /**
     * Returns the result of testing if the category has an icon to use
     * @return bool Result of testing if {@link icon} is empty
     */
    public function hasIcon()
    {
        return !empty($this->getIcon());
    }

    /**
     * Returns the full path for the category
     * @return string The path of the category
     */
    public function getFullPath()
    {
        if (!$this->isChildCategory()) {
            return $this->getTitle();
        }
        return $this->getParent()->getFullPath() . '/' . $this->getTitle();
    }
}
