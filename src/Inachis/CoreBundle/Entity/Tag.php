<?php

namespace Inachis\Component\CoreBundle\Entity;

/**
 * Object for handling tags that are mapped to content
 * @Entity @Table(indexes={@Index(name="search_idx", columns={"title"})})
 */
class Tag
{
    /**
     * @Id @Column(type="string", unique=true, nullable=false)
     * @GeneratedValue(strategy="UUID")
     * @var string The unique identifier for the tag
     */
    protected $id;
    /**
     * @Column(type="string", length=50)
     * @var string The text for the tag
     */
    protected $title;
    
    public function __construct($title = '')
    {
        $this->setTitle($title);
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function setTitle($value)
    {
        $this->title = $value;
    }
}
