<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\Image;
use Inachis\Component\CoreBundle\Entity\ImageManager;
use Mockery;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    protected $image;
    protected $manager;
    protected $properties = array();
    
    public function setUp()
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager');
        $this->em->shouldIgnoreMissing();
        $testDate = new \DateTime('now');
        $this->properties = array(
            'id' => 'UUID',
            'title' => 'test image',
            'description' => 'This is a test image',
            'filename' => 'imagetest.jpg',
            'filetype' => 'image/jpeg',
            'filesize' => 128,
            'checksum' => 'anImaginaryChecksum',
            'createDate' => $testDate->format('Y-m-d H:i:s'),
            'modDate' => $testDate->format('Y-m-d H:i:s'),
            'dimensionX' => 800,
            'dimensionY' => 600,
            'caption' => 'a test image',
            'altText' => 'a test image\'s descriptive alt text'
        );
        $this->manager = new ImageManager($this->em);
        $this->image = $this->manager->create($this->properties);
    }
    
    public function testSettingOfObjectProperties()
    {
        $this->image = $this->manager->create($this->properties);
        $this->assertEquals(
            $this->properties['id'],
            $this->image->getId()
        );
        $this->assertEquals(
            $this->properties['title'],
            $this->image->getTitle()
        );
        $this->assertEquals(
            $this->properties['description'],
            $this->image->getDescription()
        );
        $this->assertEquals(
            $this->properties['filename'],
            $this->image->getFilename()
        );
        $this->assertEquals(
            $this->properties['filetype'],
            $this->image->getFiletype()
        );
        $this->assertEquals(
            $this->properties['filesize'],
            $this->image->getFilesize()
        );
        $this->assertEquals(
            $this->properties['checksum'],
            $this->image->getChecksum()
        );
        $this->assertEquals(
            $this->properties['createDate'],
            $this->image->getCreateDate()
        );
        $this->assertEquals(
            $this->properties['modDate'],
            $this->image->getModDate()
        );
        $this->assertEquals(
            $this->properties['dimensionX'],
            $this->image->getDimensionX()
        );
        $this->assertEquals(
            $this->properties['dimensionY'],
            $this->image->getDimensionY()
        );
        $this->assertEquals(
            $this->properties['caption'],
            $this->image->getCaption()
        );
        $this->assertEquals(
            $this->properties['altText'],
            $this->image->getAltText()
        );
    }

    public function testSetCreateDateFromDateTime()
    {
        $this->image->setCreateDateFromDateTime(new \DateTime('today'));
        $this->assertEquals(
            date('Y-m-d') . ' 00:00:00',
            $this->image->getCreateDate()
        );
    }
    public function testSetModDateFromDateTime()
    {
        $this->image->setModDateFromDateTime(new \DateTime('today'));
        $this->assertEquals(
            date('Y-m-d') . ' 00:00:00',
            $this->image->getModDate()
        );
    }

    public function testValidChecksum()
    {
        $this->image->setChecksum('test');
        $this->assertEquals(true, $this->image->verifyChecksum('test'));
    }

    public function testInvalidChecksum()
    {
        $this->image->setChecksum('test');
        $this->assertEquals(false, $this->image->verifyChecksum('test1'));
    }
}
