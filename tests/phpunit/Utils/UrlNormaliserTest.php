<?php
/**
 * Created by PhpStorm.
 * User: davep
 * Date: 20/06/2018
 * Time: 15:47
 */

namespace App\Tests\phpunit\Utils;

use App\Utils\UrlNormaliser;
use PHPUnit\Framework\TestCase;

class UrlNormaliserTest extends TestCase
{
    public function testFromUri()
    {
        $this->assertEquals('some-short-text', UrlNormaliser::toUri('some short text'));
        $this->assertEquals('some-short-text', UrlNormaliser::toUri('some  short  text'));
        $this->assertEquals('some-short-text', UrlNormaliser::toUri('some short text'));
        $this->assertEquals('s0me-short-text', UrlNormaliser::toUri('s0me short text'));
        $this->assertEquals('some-short-text', UrlNormaliser::toUri('some short text $%'));
        $this->assertEquals('some-short-text', UrlNormaliser::toUri('$ some short text'));
        $this->assertEquals('some-short-text', UrlNormaliser::toUri('some_short text'));
        $this->assertEquals('some', UrlNormaliser::toUri('some short text', 5));
        $this->assertEquals('some-s', UrlNormaliser::toUri('some short text', 6));
    }

    public function testToUri()
    {
        $this->assertEquals(
            'something-really-cool',
            UrlNormaliser::fromUri('https://something.local/2018/03/12/something-really-cool')
        );
        $this->assertEquals(
            'something-really-cool',
            UrlNormaliser::fromUri('/2018/03/12/something-really-cool')
        );
        $this->assertEquals('something-really-cool', UrlNormaliser::fromUri('something-really-cool'));
    }
}
