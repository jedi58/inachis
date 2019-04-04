<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        $this->url = new Url();
    }

    public function testSetAndGetId()
    {
        $this->url->setId('test');
        $this->assertEquals('test', $this->url->getId());
    }

    public function testSetAndGetLink()
    {
        $this->url->setLink('test');
        $this->assertEquals('test', $this->url->getLink());
    }

    public function testSetAndGetLinkCanonical()
    {
        $this->url->setLinkCanonical('test');
        $this->assertEquals('test', $this->url->getLinkCanonical());
    }

    public function testSetModDateToNow()
    {
        $yesterdayDateTime = new \DateTime('yesterday');
        $this->url->setModDate($yesterdayDateTime);
        $this->url->setModDateToNow();
        $this->assertEquals(
            (new \DateTime('now'))->format('Ymd'),
            $this->url-getModDate()->format('Ymd')
        );
    }

    public function testValidateURL()
    {
        $this->url->setLink('test-link');
        $this->assertTrue($this->url->validateURL());
        $this->url->setLink('test\'s-link');
        $this->assertFalse($this->url->validateURL());
    }
}
