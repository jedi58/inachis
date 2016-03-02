<?php

namespace Inachis\Tests\AdminBundle\Entity;

use Inachis\Component\AdminBundle\Entity\User;
use Inachis\Component\AdminBundle\Entity\UserManager;
use Mockery;

/**
 * @group unit
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $user;
    protected $manager;
    protected $properties = array();
    protected $em;
    protected $repository;
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager');
        $this->em->shouldIgnoreMissing();
        $this->repository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $this->em->shouldReceive('getRepository')->andReturn($this->repository);
        $this->user = new User();
        $this->manager = new UserManager($this->em);
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
            'createDate' => $testDate->format('Y-m-d H:i:s'),
            'modDate' => $testDate->format('Y-m-d H:i:s'),
            'passwordModDate' => $testDate->format('Y-m-d H:i:s'),
            'salt' => 'thisWouldBeSomeSalt'
        );
    }
    
    public function testSettingOfObjectProperties()
    {
        $this->user = $this->manager->create($this->properties);
        $this->assertEquals(
            $this->properties['id'],
            $this->user->getId()
        );
        $this->assertEquals(
            $this->properties['username'],
            $this->user->getUsername()
        );
        $this->assertEquals(
            $this->properties['password'],
            $this->user->getPassword()
        );
        $this->assertEquals(
            $this->properties['email'],
            $this->user->getEmail()
        );
        $this->assertEquals(
            $this->properties['displayName'],
            $this->user->getDisplayName()
        );
        $this->assertEquals(
            $this->properties['avatar'],
            $this->user->getAvatar()
        );
        $this->assertEquals(
            $this->properties['isActive'],
            $this->user->isEnabled()
        );
        $this->assertEquals(
            $this->properties['isRemoved'],
            $this->user->hasBeenRemoved()
        );
        $this->assertEquals(
            $this->properties['createDate'],
            $this->user->getCreateDate()
        );
        $this->assertEquals(
            $this->properties['modDate'],
            $this->user->getModDate()
        );
        $this->assertEquals(
            $this->properties['passwordModDate'],
            $this->user->getModDate()
        );
    }

    public function testErase()
    {
        $this->user = $this->manager->create($this->properties);
        $this->user->erase();
        $this->assertEquals('', $this->user->getUsername());
        $this->assertEquals('', $this->user->getPassword());
        $this->assertEquals('', $this->user->getEmail());
        $this->assertEquals(true, $this->user->hasBeenRemoved());
    }

    public function testCredentialsExpired()
    {
        $this->user = $this->manager->create($this->properties);
        $this->user->setPasswordModDateFromDateTime(new \DateTime('31 days ago'));
        $this->assertEquals(true, $this->user->hasCredentialsExpired(30));
    }

    public function testCredentialsNotExpired()
    {
        $this->user = $this->manager->create($this->properties);
        $this->user->setPasswordModDateFromDateTime(new \DateTime('20 days ago'));
        $this->assertEquals(false, $this->user->hasCredentialsExpired(30));
    }

    public function testValidEmail()
    {
        $this->user = $this->manager->create($this->properties);
        $this->assertEquals(true, $this->user->validateEmail());
        $this->user->setEmail('test.apo-str\'ophes@test-domain.co.uk');
        $this->assertEquals(true, $this->user->validateEmail());
    }

    public function testInvalidEmail()
    {
        $this->user = $this->manager->create($this->properties);
        $this->user->setEmail('bademail-address.com');
        $this->assertEquals(false, $this->user->validateEmail());
        $this->user->setEmail('bademail-address@test');
        $this->assertEquals(false, $this->user->validateEmail());
    }

    public function testValidPasswordHash()
    {
        $this->user = $this->manager->create($this->properties);
        $this->user->setPasswordHash('test-Password123');
        $this->assertEquals(true, $this->user->validatePasswordHash('test-Password123'));
    }

    public function testInvalidPasswordHash()
    {
        $this->user = $this->manager->create($this->properties);
        $this->user->setPasswordHash('test-Password123');
        $this->assertEquals(false, $this->user->validatePasswordHash('test-password123'));
    }
}
