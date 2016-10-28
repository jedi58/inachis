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
    
//    public function testGetAll()
//    {
//        $users = array();
//        $this->user = $this->manager->create($this->properties);
//        $users[] = $this->user;
//        $this->user = $this->manager->create($this->properties);
//        $this->user->setUsername('test2');
//        $users[] = $this->user;
//
//        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
//            ->andReturn($users);
//        $this->assertSame($users, $this->manager->getAll(10, 0));
//    }

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
        $this->user->setPassword(
            '314201007310708e9c4f4bff4b5ca9a6f8ffb59e49971cd4503cd22d2eebf6ac8b00eb0eef' .
            '6614b5daeb08143b86d9b855e20a7bcca60d1193e871a9af87072d5cdc1cadd495898c7bc8' .
            'b0052233220832801fd81d04a1bf647a0815c483af6c5661e7e6854515b3cbf0b975bb9bdb7550');
        $this->user->setDisplayName(
            '314201002e25077e3c2c8dc4d7eae38f2e8d0c8ec985ad4bd6e05bad0968447161055123d7b' .
            'd136e72589380c03738caa14b1c471cb6572464214fbb9b1df4e15722d18b8443f84e1040a3' .
            '8c56567b93f4183dca478c46aeb114b1c0c1efde4fd32da44ac1');
        $this->assertSame($this->user, $this->manager->getByUsername('test'));
    }

    public function testGetByIdRaw()
    {
        $this->user = $this->manager->create($this->properties);
        $this->repository->shouldReceive('find')->with($this->user->getId())->andReturn($this->user);
        $this->assertSame($this->user, $this->manager->getByIdRaw($this->user->getId()));
    }
}
