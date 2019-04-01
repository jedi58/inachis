<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\Image;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ImageTest extends TestCase
{
    protected $image;

    public function setUp()
    {
        $this->image = new Image();
    }

    public function testGetAndSetId()
    {
        $this->image->setId('test');
        $this->assertEquals('test', $this->image->getId());
    }

    public function testGetAndSetTitle()
    {
        $this->image->setTitle('test');
        $this->assertEquals('test', $this->image->getTitle());
    }

    public function testGetAndSetDescription()
    {
        $this->image->setDescription('test');
        $this->assertEquals('test', $this->image->getDescription());
    }

    public function testGetAndSetFilename()
    {
        $this->image->setFilename('test');
        $this->assertEquals('test', $this->image->getFilename());
    }

    public function testInvalidFiletype()
    {
        $this->assertFalse($this->image->isValidFiletype('test'));
    }

    public function testValidFiletype()
    {
        $this->assertTrue($this->image->isValidFiletype('image/jpeg'));
    }

    public function testGetAndSetFiletype()
    {
        $this->image->setFiletype('image/jpeg');
        $this->assertEquals('image/jpeg', $this->image->getFiletype());
        $this->expectException(FileException::class);
        $this->image->setFiletype('test');
    }

    public function testSetAndGetFilesize()
    {
        $this->image->setFilesize(100);
        $this->assertEquals(100, $this->image->getFilesize());
        $this->expectException(FileException::class);
        $this->image->setFilesize(-100);
    }

    public function testSetAndGetChecksum()
    {
        $this->image->setChecksum('test');
        $this->assertEquals('test', $this->image->getChecksum());
        $this->assertTrue($this->image->verifyChecksum('test'));
        $this->assertFalse($this->image->verifyChecksum('test123'));
    }

    public function testSetAndGetCreateDate()
    {
        $this->image->setCreateDate('1970-01-02 01:34:56');
        $this->assertEquals('1970-01-02 01:34:56', $this->image->getCreateDate());
        $now = new \DateTime('now');
        $this->image->setCreateDateFromDateTime($now);
        $this->assertEquals($now->format('Y-m-d H:i:s'), $this->image->getCreateDate());
    }

    public function testSetAndGetModDate()
    {
        $this->image->setModDate('1970-01-02 01:34:56');
        $this->assertEquals('1970-01-02 01:34:56', $this->image->getModDate());
        $now = new \DateTime('now');
        $this->image->setModDateFromDateTime($now);
        $this->assertEquals($now->format('Y-m-d H:i:s'), $this->image->getModDate());
    }

    public function testSetAndGetDimensionX()
    {
        $this->image->setDimensionX(100);
        $this->assertEquals(100, $this->image->getDimensionX());
    }

    public function testSetAndGetDimensionY()
    {
        $this->image->setDimensionY(100);
        $this->assertEquals(100, $this->image->getDimensionY());
    }

    public function testSetAndGetCaption()
    {
        $this->image->setCaption('test');
        $this->assertEquals('test', $this->image->getCaption());
    }

    public function testSetAndGetAltText()
    {
        $this->image->setAltText('test');
        $this->assertEquals('test', $this->image->getAltText());
    }
}
