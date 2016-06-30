<?php

namespace Inachis\Tests\CoreBundle\Routing;

use Inachis\Component\CoreBundle\Routing\Route;
use Inachis\Component\CoreBundle\Exception\RouteConfigException;

/**
 * @Entity
 * @group unit
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    public $route;

    public function setUp()
    {
        $this->route = new Route();
    }

    public function testHydrateException()
    {
        $this->setUp();
        try {
            $this->route->hydrate(null);
        } catch (RouteConfigException $exception) {
            $this->assertContains('Route could not be parsed', $exception->getMessage());
        }
    }

    public function testGetMethodsDefault()
    {
        $this->setUp();
        $this->assertEquals(array('GET'), $this->route->getMethods());
    }

    public function testSetMethodsDefault()
    {
        $this->setUp();
        $this->route->setDefaultMethods();
        $this->assertEquals(array('GET'), $this->route->getMethods());
    }

    public function testSetActionEmpty()
    {
        $this->setUp();
        try {
            $this->route->setAction('test');
        } catch (RouteConfigException $exception) {
            $this->assertContains('Invalid function name for route', $exception->getMessage());
        }
    }

    public function testSetPathEmpty()
    {
        $this->setUp();
        try {
            $this->route->setPath('');
        } catch (RouteConfigException $exception) {
            $this->assertContains('Path for routing cannot be empty', $exception->getMessage());
        }
    }

    public function testFormatRoute()
    {
        $this->setUp();
        $this->route->setMethods(array('GET', 'POST'));
        $this->route->setPath('/test');
        $this->route->setAction('Inachis\Tests\CoreBundle\Routing\RouteTest::testFormatRoute');
        $this->assertEquals(
            'Methods: GET,POST' . PHP_EOL .
            'Path:    /test' . PHP_EOL .
            'Action:  Inachis\Tests\CoreBundle\Routing\RouteTest::testFormatRoute' . PHP_EOL,
            $this->route->formatRoute()
        );
    }
}
