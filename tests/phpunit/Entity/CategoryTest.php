<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    protected $category;

    public function setUp()
    {
        $this->category = new Category();
    }

    public function testGetAndSetId()
    {
        $this->category->setId('test-id');
        $this->assertEquals('test-id', $this->category->getId());
    }

    public function testGetAndSetTitle()
    {
        $this->category->setTitle('test');
        $this->assertEquals('test', $this->category->getTitle());
    }

    public function testGetAndSetDescription()
    {
        $this->category->setDescription('test');
        $this->assertEquals('test', $this->category->getDescription());
    }

    public function testGetAndSetImage()
    {
        $this->category->setImage('test');
        $this->assertEquals('test', $this->category->getImage());
    }

    public function testGetAndSetIcon()
    {
        $this->category->setIcon('test');
        $this->assertEquals('test', $this->category->getIcon());
    }

    public function testGetAndSetParent()
    {
        $this->category->setParent(new Category('test-parent'));
        $this->assertEquals('test-parent', $this->category->getParent()->getTitle());
    }

    public function testAddChild()
    {
        $this->category->addChild(new Category('first child'));
        $this->assertNotEmpty($this->category->getChildren());
    }

    public function testIsRootCategory()
    {
        $this->assertTrue($this->category->isRootCategory());
        $this->category->setParent(new Category('Darth Vader'));
        $this->assertFalse($this->category->isRootCategory());
    }

    public function testHasImage()
    {
        $this->assertFalse($this->category->hasImage());
        $this->category->setImage('test');
        $this->assertTrue($this->category->hasImage());
    }

    public function testHasIcon()
    {
        $this->assertFalse($this->category->hasIcon());
        $this->category->setIcon('test');
        $this->assertTrue($this->category->hasIcon());
    }

    public function testGetFullPath()
    {
        $this->category->setTitle('Darth Vader');
        $this->category->addChild(new Category('Luke Skywalker'));
        $this->category->getChildren()[0]->setParent(new Category('Darth Vader'));
        $this->assertEquals(
            'Darth Vader/Luke Skywalker',
            $this->category->getChildren()[0]->getFullPath()
        );
    }
}
