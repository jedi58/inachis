<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\User;
use App\Exception\InvalidTimezoneException;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $user;

    public function setUp() : void
    {
        $this->user = new User();

        parent::setUp();
    }

    public function testSetAndGetId()
    {
        $this->user->setId('test');
        $this->assertEquals('test', $this->user->getId());
    }

    public function testSetAndGetUsername()
    {
        $this->user->setUsername('test');
        $this->assertEquals('test', $this->user->getUsername());
    }

    public function testSetAndGetPassword()
    {
        $this->user->setPassword('test');
        $this->assertEquals('test', $this->user->getPassword());
    }

    public function testSetAndGetPlainPassword()
    {
        $this->user->setPlainPassword('test');
        $this->assertEquals('test', $this->user->getPlainPassword());
    }

    public function testSetAndGetEmail()
    {
        $this->user->setEmail('test');
        $this->assertEquals('test', $this->user->getEmail());
    }

    public function testSetAndGetDisplayName()
    {
        $this->user->setDisplayName('test');
        $this->assertEquals('test', $this->user->getDisplayName());
    }

    public function testSetAndGetAvatar()
    {
        $this->user->setAvatar('test');
        $this->assertEquals('test', $this->user->getAvatar());
    }

    public function testIsEnabled()
    {
        $this->assertTrue($this->user->isEnabled());
        $this->user->setActive(false);
        $this->assertFalse($this->user->isEnabled());
    }

    public function testHasBeenRemoved()
    {
        $this->assertFalse($this->user->hasBeenRemoved());
        $this->user->setRemoved(true);
        $this->assertTrue($this->user->hasBeenRemoved());
    }

    public function testSetAndGetCreateDate()
    {
        $currentDateTime = new \DateTime('now');
        $this->user->setCreateDate($currentDateTime);
        $this->assertEquals($currentDateTime, $this->user->getCreateDate());
    }

    public function testSetAndGetModDate()
    {
        $currentDateTime = new \DateTime('now');
        $this->user->setModDate($currentDateTime);
        $this->assertEquals($currentDateTime, $this->user->getModDate());
    }

    public function testSetAndGetPasswordModDate()
    {
        $currentDateTime = new \DateTime('now');
        $this->user->setPasswordModDate($currentDateTime);
        $this->assertEquals($currentDateTime, $this->user->getPasswordModDate());
    }

    public function testHasCredentialsExpired()
    {
        $this->assertFalse($this->user->hasCredentialsExpired());
        $this->user->setPasswordModDate(new \DateTime('-20 days'));
        $this->assertTrue($this->user->hasCredentialsExpired(10));
    }

    public function testValidateEmail()
    {
        $this->user->setEmail('test@test.com');
        $this->assertTrue($this->user->validateEmail());
        $this->user->setEmail('test@test.co.uk');
        $this->assertTrue($this->user->validateEmail());
        $this->user->setEmail('test.o\'test@test.com');
        $this->assertTrue($this->user->validateEmail());
        $this->user->setEmail('test+something@test.com');
        $this->assertTrue($this->user->validateEmail());
        $this->user->setEmail('test_at_test.com');
        $this->assertFalse($this->user->validateEmail());
    }

    public function testSerialize()
    {
        $this->user->setId('id');
        $this->user->setUsername('username');
        $this->user->setPassword('password');
        $this->user->setActive(true);
        $this->assertEquals(
            'a:4:{i:0;s:2:"id";i:1;s:8:"username";i:2;s:8:"password";i:3;b:1;}',
            $this->user->serialize()
        );
    }

    public function testUnserialize()
    {
        $this->user->unserialize('a:4:{i:0;s:2:"id";i:1;s:8:"username";i:2;s:8:"password";i:3;b:1;}');
        $this->assertEquals('id', $this->user->getId());
        $this->assertEquals('username', $this->user->getUsername());
        $this->assertEquals('password', $this->user->getPassword());
        $this->assertTrue($this->user->isEnabled());
    }

    public function testGetRoles()
    {
        $this->user->setRoles();
        $this->assertEquals([ 'ROLE_ADMIN', 'ROLE_USER' ], $this->user->getRoles());
    }

    public function testEraseCredentials()
    {
        $this->user->setPlainPassword('test');
        $this->assertEquals('test', $this->user->getPlainPassword());
        $this->user->eraseCredentials();
        $this->assertEquals('', $this->user->getPlainPassword());
    }

    public function testErase()
    {
        $this->assertNull($this->user->erase());
    }

    public function getSalt()
    {
        $this->assertNull($this->user->getSalt());
    }

    public function testSetAndGetTimezone()
    {
        $this->user->setTimezone('Europe/London');
        $this->assertEquals('Europe/London', $this->user->getTimezone());
        $this->expectException(InvalidTimezoneException::class);
        $this->user->setTimezone('Alpha Centauri');
    }
}
