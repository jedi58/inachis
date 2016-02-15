<?php

namespace Inachis\Component\CoreBundle\Tests\Entity;

/**
 * @group unit
 */
use Inachis\Component\CoreBundle\Entity\Category;

class CategoryTest extends \PHPUnit_Framework_TestCase
{
    protected $category;
    protected $properties = array();
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->category = new Category();
        $this->properties = array(
            'id' => 'UUID',
            'title' => 'Test Category',
            'description' => '<p>A description of the category</p>',
            'image' => 'UUID',
            'icon' => 'UUID',
            'parent' => 'UUID'
        );
    }
    
    private function initialiseDefaultObject()
    {
        $this->category = new Category();
        $this->category->setId($this->properties['id']);
        $this->category->setTitle($this->properties['title']);
        $this->category->setDescription($this->properties['description']);
        $this->category->setImage($this->properties['image']);
        $this->category->setIcon($this->properties['icon']);
        $this->category->setParent($this->properties['parent']);
    }
    
    public function testSettingOfObjectProperties()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(
            $this->properties['id'],
            $this->category->getId()
        );
        $this->assertEquals(
            $this->properties['title'],
            $this->category->getTitle()
        );
        $this->assertEquals(
            $this->properties['description'],
            $this->category->getDescription()
        );
        $this->assertEquals(
            $this->properties['image'],
            $this->category->getImage()
        );
        $this->assertEquals(
            $this->properties['icon'],
            $this->category->getIcon()
        );
        $this->assertEquals(
            $this->properties['parent'],
            $this->category->getParent()
        );
    }
    
    public function testParentCategory()
    {
        $this->initialiseDefaultObject();
        $this->category->setParent('');
        $this->assertEquals(true, $this->category->isRootCategory());
    }
    
    public function testNotParentCategory()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(false, $this->category->isRootCategory());
    }
    
    public function testChildCategory()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(true, $this->category->isChildCategory());
    }
    
    public function testNotChildCategory()
    {
        $this->initialiseDefaultObject();
        $this->category->setParent('');
        $this->assertEquals(false, $this->category->isChildCategory());
    }
    
    public function testCategoryHasImage()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(true, $this->category->hasImage());
    }
    
    public function testCategoryDoesNotHaveImage()
    {
        $this->initialiseDefaultObject();
        $this->category->setImage('');
        $this->assertEquals(false, $this->category->hasImage());
    }
    
    public function testCategoryHasIcon()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(true, $this->category->hasIcon());
    }
    
    public function testCategoryDoesNotHaveIcon()
    {
        $this->initialiseDefaultObject();
        $this->category->setIcon('');
        $this->assertEquals(false, $this->category->hasIcon());
    }
}
