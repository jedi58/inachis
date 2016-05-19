<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\User;
use Inachis\Component\CoreBundle\Entity\UserManager;
use Mockery;

/**
 * @Entity
 * @group unit
 */
class UserManagerTest extends \PHPUnit_Framework_TestCase
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

        $testDate = new \DateTime('now');
        $this->properties = array(
            'id' => 'UUID',
            'username' => 'test',
            'password' => 'thisShouldBeAPassword',
            'email' => 'test@example.com',
            'displayName' => 'Test User',
            'avatar' => 'UUID',
            'isActive' => true,
            'isRemoved' => false,
            'createDate' => $testDate,
            'modDate' => $testDate,
            'passwordModDate' => $testDate,
            'salt' => 'thisWouldBeSomeSalt'
        );
        $this->manager = new UserManager($this->em);
        $this->user = $this->manager->create($this->properties);
    }
    
    public function testGetAll()
    {
        $users = array();
        $this->user = $this->manager->create($this->properties);
        $users[] = $this->user;
        $this->user = $this->manager->create($this->properties);
        $this->user->setUsername('test2');
        $users[] = $this->user;
        
        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
            ->andReturn($users);
        $this->assertSame($users, $this->manager->getAll(10, 0));
    }
    
    public function testGetById()
    {
        $this->user = $this->manager->create($this->properties);
        $this->repository->shouldReceive('find')->with(1)
            ->andReturn($this->user);
        $this->assertSame($this->user, $this->manager->getById(1));
    }

    public function testSave()
    {
        $this->user = $this->manager->create($this->properties);
        $this->em->setMethods(array('persist', 'flush'));
        $this->repository->shouldReceive('persist')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->save($this->user));
    }

    public function testRemove()
    {
        $this->user = $this->manager->create($this->properties);
        $this->em->setMethods(array('remove', 'flush'));
        $this->repository->shouldReceive('remove')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->remove($this->user));
    }

    public function testGetByUsername()
    {
        $this->user = $this->manager->create($this->properties);
        $this->repository->shouldReceive('findOneBy')->with(array(
            'username' => 'test'
        ))->andReturn($this->user);
        $this->assertSame($this->user, $this->manager->getByUsername('test'));
    }
}
