<?php

namespace Inachis\Component\CoreBundle\Tests\Entity;

use Inachis\Component\CoreBundle\Entity\Category;
use Inachis\Component\CoreBundle\Entity\CategoryManager;
use Mockery;

/**
 * @Entity
 * @group unit
 */
class CategoryManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $em;
    protected $manager;
    protected $properties = array();
    protected $repository;
    protected $category;
    
    public function setUp()
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager');
        $this->em->shouldIgnoreMissing();
        
        $this->repository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $this->em->shouldReceive('getRepository')->andReturn($this->repository);

        $this->properties = array(
            'id' => 'UUID',
            'title' => 'My Test Category',
            'description' => 'This is a test category',
            'image' => 'image-UUID',
            'icon' => 'icon-UUID',
            'parent' => 'parent-UUID'
        );
        $this->manager = new CategoryManager($this->em);
        $this->category = $this->manager->create();
    }
    
    private function initialiseDefaultObject()
    {
        $this->category = $this->manager->create();
        $this->category->setId($this->properties['id']);
        $this->category->setTitle($this->properties['title']);
        $this->category->setDescription($this->properties['description']);
        $this->category->setImage($this->properties['image']);
        $this->category->setIcon($this->properties['icon']);
        $this->category->setParent($this->properties['parent']);
    }
    
    public function testGetAll()
    {
        $categories = array();
        $this->initialiseDefaultObject();
        $categories[] = $this->category;
        $this->initialiseDefaultObject();
        $this->category->setTitle('Another category');
        $categories[] = $this->category;
        
        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
            ->andReturn($categories);
        $this->assertSame($categories, $this->manager->getAll(10, 0));
    }
    
    public function testGetById()
    {
        $this->category = $this->manager->create();
        $this->repository->shouldReceive('find')->with(1)
            ->andReturn($this->category);
        $this->assertSame($this->category, $this->manager->getById(1));
    }

    public function testSave()
    {
        $this->category = $this->manager->create();
        $this->em->setMethods(array('persist', 'flush'));
        $this->repository->shouldReceive('persist')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->save($this->category));
    }

    public function testRemove()
    {
        $this->category = $this->manager->create();
        $this->em->setMethods(array('remove', 'flush'));
        $this->repository->shouldReceive('remove')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->remove($this->category));
    }
}
