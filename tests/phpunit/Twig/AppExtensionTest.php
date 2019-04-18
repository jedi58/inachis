<?php

namespace App\Tests\phpunit\Utils;

use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;

class AppExtensionTest extends TestCase
{
    protected $appExtension;

    public function setUp()
    {
        $this->appExtension = new AppExtension();
    }

    public function testActiveMenuFilter() : void
    {
        $this->assertEquals('menu__active', $this->appExtension->activeMenuFilter('test', 'test'));
        $this->assertEmpty('', $this->appExtension->activeMenuFilter('test', 'test23'));
    }
}
