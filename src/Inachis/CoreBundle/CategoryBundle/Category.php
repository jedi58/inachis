<?php

namespace Inachis\Core\CategoryBundle;

/**
 * Object for handling categories on a site
 * @Entity @Table
 */
class Category
{
    /**
     * @Id @Column(type="string", unique=true, nullable=false)
     * @GeneratedValue(strategy="UUID")
     * @var string
     */
    protected $id;
    /**
     * @Column(type="string", length=255, nullable=false)
     * @var string The name of the category
     */
    protected $title;
    /**
     * @Column(type="text")
     * @var string Description of the category
     */
    protected $description;
    /**
     * @Column(type="string", length=255)
     * @var string The UUID of the image, or the image path
     */
    protected $image;
    /**
     * @Column(type="string", length=255)
     * @var string The UUID of the image, or the image path
     */
    protected $icon;
    /**
     * @Column(type="string", length=255)
     * @var string The UUID of the parent category if applicable
     */
    protected $parent;
    
    public function __construct($title = '', $description = '', $parent = '')
    {
        $this->setTitle($title);
        $this->setDescription($description);
        $this->setParent($parent);
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
     * @return string The UUID of the {@link Category} parent if applicable
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Sets the value of {@link id}
     * @param string $value The UUID of the {@link Category}
     */
    public function setId($value)
    {
        $this->id = $value;
    }
    /**
     * Sets the value of {@link title}
     * @param string $value The title of the {@link Category}
     */
    public function setTitle($value)
    {
        $this->title = $value;
    }
    /**
     * Sets the value of {@link description}
     * @param string $value The description of the {@link Category}
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }
    /**
     * Sets the value of {@link image}
     * @param string $value The UUID or URL of the image for {@link Category}
     */
    public function setImage($value)
    {
        $this->image = $value;
    }
    /**
     * Sets the value of {@link icon}
     * @param string $value The UUID or URL of the image for {@link Category}
     */
    public function setIcon($value)
    {
        $this->icon = $value;
    }
    /**
     * Sets the value of {@link parent}
     * @param string $value The UUID of the {@link Category} parent if applicable
     */
    public function setParent($value)
    {
        $this->parent = $value;
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
}
