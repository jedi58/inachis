<?php

namespace Inachis\Tests\CoreBundle\Entity;

/**
 * @group unit
 */
use Doctrine\Common\Collections\ArrayCollection;
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
            'parent' => null
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
        $this->initialiseChildCategoryObject();
    }

    public function initialiseChildCategoryObject()
    {
        $childCategory = new Category('a test child category');
        $childCategory->setParent(new Category('Test Category'));
        $this->category->addChild($childCategory);
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
        $this->assertEquals(true, $this->category->isRootCategory());
    }
    
    public function testNotParentCategory()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(false, $this->category->getChildren()[0]->isRootCategory());
    }
    
    public function testChildCategory()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(true, $this->category->getChildren()[0]->isChildCategory());
    }
    
    public function testNotChildCategory()
    {
        $this->initialiseDefaultObject();
        $this->category->setParent(null);
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

    public function testGetFullPath()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals('Test Category', $this->category->getFullPath());
        $this->assertEquals(
            'Test Category/a test child category',
            $this->category->getChildren()[0]->getFullPath()
        );
    }
}
