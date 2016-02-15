<?php

namespace Inachis\Component\CoreBundle\Tests\Entity;

use Inachis\Component\CoreBundle\Entity\UrlManager;
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
            'contentType' => 'Page',
            'contentId' => 'UUID',
            'link' => 'phpunit-test',
            'default' => true,
            'createDate' => new \DateTime('yesterday'),
            'modDate' => new \DateTime('now')
        );
        $this->manager = new UrlManager($this->em);
        $this->url = $this->manager->create();
    }
    
    private function initialiseDefaultObject()
    {
        $this->url = $this->manager->create();
        $this->url->setId($this->properties['id']);
        $this->url->setContentType($this->properties['contentType']);
        $this->url->setContentId($this->properties['contentId']);
        $this->url->setLink($this->properties['link']);
        $this->url->setDefault($this->properties['default']);
    }
    
    public function testGetAll()
    {
        $urls = array();
        $url = $this->manager->create();
        $url->setId(1);
        $url->setLink('test-1');
        $urls[] = $url;
        $url = $this->manager->create();
        $url->setId(2);
        $url->setLink('test-2');
        $urls[] = $url;
        
        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
            ->andReturn($urls);
        $this->assertSame($urls, $this->manager->getAll(10, 0));
    }
    
    public function testGetById()
    {
        $this->url = $this->manager->create();
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->url);
        $this->assertSame($this->url, $this->manager->getById(1));
    }
    
    public function testGetAllForContentTypeAndIdReturnsSingle()
    {
        $this->url = $this->manager->create();
        $this->repository->shouldReceive('findBy')
            ->with(
                array(
                    'contentType' => 'Page',
                    'contentId' => '1'
                    )
            )->andReturn($this->url);
        $this->assertSame(
            $this->url,
            $this->manager->getAllForContentTypeAndId('Page', 1)
        );
    }
    
    public function testGetAllForContentTypeAndIdReturnsMultiple()
    {
        $urls = array();
        $this->url = $this->manager->create();
        $this->url->setContentType('Page');
        $this->url->setContentId(2);
        $this->url->setDefault(true);
        $this->url->setLink('test-link1');
        $urls[] = $this->url;
        $this->url = $this->manager->create();
        $this->url->setContentType('Page');
        $this->url->setContentId(2);
        $this->url->setDefault(false);
        $this->url->setLink('test-link2');
        $urls[] = $this->url;
        
        $this->repository->shouldReceive('findBy')
            ->with(
                array(
                    'contentType' => 'Page',
                    'contentId' => '2'
                    )
            )->andReturn($urls);
        $this->assertSame(
            $urls,
            $this->manager->getAllForContentTypeAndId('Page', 2)
        );
    }
    
    public function testGetDefaultUrlByContentTypeAndId()
    {
        $this->initialiseDefaultObject();
        $this->repository->shouldReceive('findOneBy')
            ->with(
                array(
                    'contentType' => $this->properties['contentType'],
                    'contentId' => $this->properties['contentId'],
                    'default' => $this->properties['default']
                    )
            )
            ->andReturn($this->url);
        $this->assertSame(
            $this->url,
            $this->manager->getDefaultUrlByContentTypeAndId(
                $this->properties['contentType'],
                $this->properties['contentId']
            )
        );
    }
}
