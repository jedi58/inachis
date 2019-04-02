<?php

namespace App\Tests\phpunit;

use App\Kernel;
use PHPUnit\Framework\TestCase;

class KernelTest extends TestCase
{
    protected $kernel;

    public function setUp()
    {
        $this->kernel = new Kernel('test', false);
    }

    public function testGetCacheDir()
    {
        $this->assertEquals(
            str_replace('/tests/phpunit', '/var/cache/test', __DIR__),
            $this->kernel->getCacheDir()
        );
    }

    public function testGetLogDir()
    {
        $this->assertEquals(
            str_replace('/tests/phpunit', '/var/log', __DIR__),
            $this->kernel->getLogDir()
        );
    }
}
