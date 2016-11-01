<?php

namespace Inachis\Tests\CoreBundle;

use Inachis\Component\CoreBundle\Security\Authentication;

/**
 * @Entity
 * @group unit
 */
class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    protected $auth;

    public function setUp()
    {
        $this->auth = new Authentication();
    }

    public function testGetUserManager()
    {
        $this->assertInstanceOf(
            '\Inachis\Component\CoreBundle\Entity\UserManager',
            $this->auth->getUserManager()
        );
    }

    public function testIsAuthenticated()
    {
        $this->assertEquals(false, $this->auth->isAuthenticated());
    }

    public function testGetSessionPersistFalse()
    {
        unset($_COOKIE['NX03']);
        unset($_COOKIE['NX06']);
        $this->assertEquals(false, $this->auth->getSessionPersist('test-agent'));
    }
}
