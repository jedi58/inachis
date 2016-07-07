<?php

namespace Inachis\Tests\CoreBundle\Routing;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Routing\RoutingManager;

/**
 * @Entity
 * @group unit
 * @todo add tests for dispatch(), registerViewHandler(), and registerErrorHandlers()
 */
class RoutingManagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Application::getInstance('dev');
    }

    public function testLoadEmptyNamespace()
    {
        try {
            Application::getInstance()->getRouter()->load();
        } catch (\Exception $exception) {
            $this->assertContains('No route namespaces defined', $exception->getMessage());
        }
    }
}
