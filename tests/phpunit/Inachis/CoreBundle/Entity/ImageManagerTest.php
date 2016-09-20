<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\ImageManager;
use Mockery;

/**
 * @Entity
 * @group unit
 */
class ImageManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $em;
    protected $manager;
    protected $properties = array();
    protected $repository;
    protected $image;
    
    public function setUp()
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager');
        $this->em->shouldIgnoreMissing();
        $this->repository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $this->em->shouldReceive('getRepository')->andReturn($this->repository);
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
        $this->url = $this->manager->create($this->properties);
    }
    
//    public function testGetAll()
//    {
//        $images = array();
//        $image = $this->manager->create($this->properties);
//        $image->setId(1);
//        $image->setFilename('test-1.jpg');
//        $images[] = $image;
//        $image = $this->manager->create($this->properties);
//        $image->setId(2);
//        $image->setFilename('test-2.png');
//        $images[] = $image;
//
//        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
//            ->andReturn($images);
//        $this->assertSame($images, $this->manager->getAll(10, 0));
//    }
    
    public function testGetById()
    {
        $this->image = $this->manager->create($this->properties);
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->image);
        $this->assertSame($this->image, $this->manager->getById(1));
    }
    
    public function testGetByFilename()
    {
        $this->image = $this->manager->create($this->properties);
        $this->repository
            ->shouldReceive('findOneBy')
            ->with(
                array(
                    'filename' => 'test.jpg'
                )
            )->andReturn($this->image);
        $this->assertSame(
            $this->image,
            $this->manager->getByFilename('test.jpg')
        );
    }

    public function testSave()
    {
        $this->image = $this->manager->create($this->properties);
        $this->em->setMethods(array('persist', 'flush'));
        $this->repository->shouldReceive('persist')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->save($this->image));
    }

    public function testRemove()
    {
        $this->image = $this->manager->create($this->properties);
        $this->em->setMethods(array('remove', 'flush'));
        $this->repository->shouldReceive('remove')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->remove($this->image));
    }
}
