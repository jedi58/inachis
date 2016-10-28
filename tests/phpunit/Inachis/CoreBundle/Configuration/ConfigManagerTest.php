<?php

namespace Inachis\Tests\CoreBundle;

use Inachis\Component\CoreBundle\Configuration\ConfigManager;

/**
 * @Entity
 * @group unit
 */
class ConfigManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    public function setUp()
    {
        $this->config = new ConfigManager();
    }
    public function testLoad()
    {
        $config = ConfigManager::getInstance()->load('core', 'json', 'routing/');
        $this->assertEquals(true, is_array($config));
    }

    public function testLoadAll()
    {
        $config = ConfigManager::getInstance()->loadAll();
        $this->assertEquals(true, is_array($config));
    }

    public function testLoadAllFromLocation()
    {
        $config = ConfigManager::getInstance()->loadAllFromLocation('');
        $this->assertEquals(true, is_array($config));
    }
}
