<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\UrlManager;
use Inachis\Component\CoreBundle\Entity\Page;
use Mockery;

/**
 * @Entity
 * @group unit
 */
class UrlManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $em;
    protected $manager;
    protected $properties = array();
    protected $repository;
    protected $url;
    
    public function setUp()
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager');
        $this->em->shouldIgnoreMissing();
        
        $this->repository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $this->em->shouldReceive('getRepository')->andReturn($this->repository);

        $this->properties = array(
            'id' => 'UUID',
            'content' => new Page(),
            'link' => 'phpunit-test',
            'default' => true,
            'createDate' => new \DateTime('yesterday'),
            'modDate' => new \DateTime('now')
        );
        $this->manager = new UrlManager($this->em);
        $this->url = $this->manager->create($this->properties);
    }
    
    private function initialiseDefaultObject()
    {
        $this->url = $this->manager->create($this->properties);
        $this->url->setId($this->properties['id']);
        $this->url->setContent($this->properties['content']);
        $this->url->setLink($this->properties['link']);
        $this->url->setDefault($this->properties['default']);
    }
    
    public function testGetAll()
    {
        $urls = array();
        $url = $this->manager->create($this->properties);
        $url->setId(1);
        $url->setLink('test-1');
        $urls[] = $url;
        $url = $this->manager->create($this->properties);
        $url->setId(2);
        $url->setLink('test-2');
        $urls[] = $url;
        
        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
            ->andReturn($urls);
        $this->assertSame($urls, $this->manager->getAll(10, 0));
    }
    
    public function testGetById()
    {
        $this->url = $this->manager->create($this->properties);
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->url);
        $this->assertSame($this->url, $this->manager->getById(1));
    }
    
    // public function testGetAllForContentTypeAndIdReturnsSingle()
    // {
    //     $this->url = $this->manager->create($this->properties);
    //     $this->repository->shouldReceive('findBy')
    //         ->with(
    //             array(
    //                 'content' => null
    //             )
    //         )->andReturn($this->url);
    //     $this->assertSame(
    //         $this->url,
    //         $this->manager->getAllForContentTypeAndId('Page', 1)
    //     );
    // }
    
    // public function testGetAllForContentTypeAndIdReturnsMultiple()
    // {
    //     $urls = array();
    //     $this->url = $this->manager->create();
    //     $this->url->setContentType('Page');
    //     $this->url->setContentId(2);
    //     $this->url->setDefault(true);
    //     $this->url->setLink('test-link1');
    //     $urls[] = $this->url;
    //     $this->url = $this->manager->create();
    //     $this->url->setContentType('Page');
    //     $this->url->setContentId(2);
    //     $this->url->setDefault(false);
    //     $this->url->setLink('test-link2');
    //     $urls[] = $this->url;
        
    //     $this->repository->shouldReceive('findBy')
    //         ->with(
    //             array(
    //                 'contentType' => 'Page',
    //                 'contentId' => '2'
    //                 )
    //         )->andReturn($urls);
    //     $this->assertSame(
    //         $urls,
    //         $this->manager->getAllForContentTypeAndId('Page', 2)
    //     );
    // }
    
    // public function testGetDefaultUrlByContentTypeAndId()
    // {
    //     $this->url = $this->manager->create($this->properties);
    //     $this->repository->shouldReceive('findOneBy')
    //         ->with(
    //             array(
    //                 'contentType' => $this->properties['contentType'],
    //                 'contentId' => $this->properties['contentId'],
    //                 'default' => $this->properties['default']
    //                 )
    //         )
    //         ->andReturn($this->url);
    //     $this->assertSame(
    //         $this->url,
    //         $this->manager->getDefaultUrlByContentTypeAndId(
    //             $this->properties['contentType'],
    //             $this->properties['contentId']
    //         )
    //     );
    // }

    public function testSave()
    {
        $this->url = $this->manager->create($this->properties);
        $this->em->setMethods(array('persist', 'flush'));
        $this->repository->shouldReceive('persist')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->save($this->url));
    }

    public function testRemove()
    {
        $this->url = $this->manager->create($this->properties);
        $this->em->setMethods(array('remove', 'flush'));
        $this->repository->shouldReceive('remove')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->remove($this->url));
    }
}
