<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\TagManager;
use Mockery;

/**
 * @Entity
 * @group unit
 */
class TagManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $em;
    protected $manager;
    protected $properties = array();
    protected $repository;
    protected $tag;
    
    public function setUp()
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager');
        $this->em->shouldIgnoreMissing();
        
        $this->repository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $this->em->shouldReceive('getRepository')->andReturn($this->repository);

        $this->properties = array(
            'id' => 'UUID',
            'title' => 'awesome-tag'
        );
        $this->manager = new TagManager($this->em);
        $this->tag = $this->manager->create($this->properties);
    }
    
//    public function testGetAll()
//    {
//        $tags = array();
//        $this->tag = $this->manager->create($this->properties);
//        $tags[] = $this->tag;
//        $this->tag = $this->manager->create($this->properties);
//        $this->tag->setTitle('test');
//        $tags[] = $this->tag;
//
//        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
//            ->andReturn($tags);
//        $this->assertSame($tags, $this->manager->getAll(10, 0));
//    }
    
    public function testGetById()
    {
        $this->tag = $this->manager->create($this->properties);
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->tag);
        $this->assertSame($this->tag, $this->manager->getById(1));
    }

    public function testSave()
    {
        $this->tag = $this->manager->create($this->properties);
        $this->em->setMethods(array('persist', 'flush'));
        $this->repository->shouldReceive('persist')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->save($this->tag));
    }

    public function testRemove()
    {
        $this->tag = $this->manager->create($this->properties);
        $this->em->setMethods(array('remove', 'flush'));
        $this->repository->shouldReceive('remove')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->remove($this->tag));
    }
}
