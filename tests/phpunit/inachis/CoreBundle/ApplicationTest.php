<?php

namespace Inachis\Tests\CoreBundle;

use Inachis\Component\CoreBundle\Application;

/**
 * @Entity
 * @group unit
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetEnvDev()
    {
        $this->assertEquals(
            'dev',
            Application::getInstance('dev')->getEnv()
        );
    }
    public function testGetEnvProd()
    {
        Application::getInstance()->setEnv('prod');
        $this->assertEquals(
            'prod',
            Application::getInstance()->getEnv()
        );
    }
    public function testGetRouter()
    {
        $this->assertInstanceOf(
            'Inachis\Component\CoreBundle\Routing\RoutingManager',
            Application::getInstance()->getRouter()
        );
    }
    public function testGetConfig()
    {
        $this->assertEquals(true, is_array(Application::getInstance()->getConfig()));
    }
    public function testGetApplicationRoot()
    {
        $this->assertEquals(
            str_replace('tests/phpunit/inachis/CoreBundle', '', __DIR__),
            Application::getInstance()->getApplicationRoot()
        );
    }
    public function testGetServiceExists()
    {
        Application::getInstance()->addService('test', 'a test function');
        $this->assertEquals('a test function', Application::getInstance()->getService('test'));
    }
    public function testGetServiceDoesNotExist()
    {
        $this->assertEquals(null, Application::getInstance()->getService('test2'));
    }
}
