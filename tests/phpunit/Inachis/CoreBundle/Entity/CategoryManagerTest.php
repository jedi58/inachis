<?php

namespace Inachis\Tests\CoreBundle\Entity;

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
        $this->category = $this->manager->create($this->properties);
    }
    
    public function testGetAll()
    {
        $categories = array();
        $this->category = $this->manager->create($this->properties);
        $categories[] = $this->category;
        $this->category = $this->manager->create($this->properties);
        $this->category->setTitle('Another category');
        $categories[] = $this->category;
        
        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
            ->andReturn($categories);
        $this->assertSame($categories, $this->manager->getAll(10, 0));
    }
    
    public function testGetById()
    {
        $this->category = $this->manager->create($this->properties);
        $this->repository->shouldReceive('find')->with(1)
            ->andReturn($this->category);
        $this->assertSame($this->category, $this->manager->getById(1));
    }

    public function testSave()
    {
        $this->category = $this->manager->create($this->properties);
        $this->em->setMethods(array('persist', 'flush'));
        $this->repository->shouldReceive('persist')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->save($this->category));
    }

    public function testRemove()
    {
        $this->category = $this->manager->create($this->properties);
        $this->em->setMethods(array('remove', 'flush'));
        $this->repository->shouldReceive('remove')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->remove($this->category));
    }
}
