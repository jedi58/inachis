<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\Revision;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RevisionTest extends TestCase
{
    protected $revision;

    public function setUp() : void
    {
        $this->revision = new Revision();

        parent::setUp();
    }

    public function testGetAndSetId()
    {
        $this->revision->setId('test');
        $this->assertEquals('test', $this->revision->getId());
    }

    public function testGetAndSetPageId()
    {
        $this->revision->setPageId('test');
        $this->assertEquals('test', $this->revision->getPageId());
    }

    public function testGetAndSetVersionNumber()
    {
        $this->revision->setVersionNumber(223);
        $this->assertEquals(223, $this->revision->getVersionNumber());
        $this->expectException(\Exception::class);
        $this->revision->setVersionNumber(-1);
    }

    public function testGetAndSetModDate()
    {
        $testDate = new \DateTime();
        $this->revision->setModDate($testDate);
        $this->assertEquals($testDate, $this->revision->getModDate());
    }

    public function testGetAndSetUser()
    {
        $testUser = new User();
        $this->revision->setUser($testUser);
        $this->assertEquals($testUser, $this->revision->getUser());
    }

    public function testGetAndSetAction()
    {
        $testUser = new User();
        $this->revision->setAction('Updated content');
        $this->assertEquals('Updated content', $this->revision->getAction());
    }

    public function testGetAndSetTitle()
    {
        $this->revision->setTitle('Test');
        $this->assertEquals('Test', $this->revision->getTitle());
    }

    public function testGetAndSetSubTitle()
    {
        $this->revision->setSubTitle('Test');
        $this->assertEquals('Test', $this->revision->getSubTitle());
    }

    public function testGetAndSetContent()
    {
        $this->revision->setContent('Test');
        $this->assertEquals('Test', $this->revision->getContent());
    }
}
