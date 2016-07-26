<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\Page;
use Inachis\Component\CoreBundle\Entity\PageManager;
use Inachis\Component\CoreBundle\Entity\User;
use Mockery;

/**
 * @Entity
 * @group unit
 */
class PageManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $em;
    protected $manager;
    protected $properties = array();
    protected $repository;
    protected $page;
    
    public function setUp()
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager');
        $this->em->shouldIgnoreMissing();
        
        $this->repository = Mockery::mock('Doctrine\ORM\EntityRepository');
        $this->em->shouldReceive('getRepository')->andReturn($this->repository);

        $this->properties = array(
            'id' => 'UUID',
            'title' => 'My awesome test page',
            'subTitle' => 'The first page',
            'content' => '<p>This is a test page.</p>',
            'author' => new User(),
            'featureImage' => 'UUID',
            'featureSnippet' => 'This is a short excerpt of the page',
            'status' => Page::DRAFT,
            'visibility' => Page::VIS_PUBLIC,
            'timezone' => 'UTC',
            'postDate' => new \DateTime(),
            'modDate' => new \DateTime(),
            'password' => '',
            'allowComments' => true
        );
        $this->manager = new PageManager($this->em);
        $this->page = $this->manager->create($this->properties);
    }
    
    public function testGetAll()
    {
        $pages = array();
        $this->page = $this->manager->create($this->properties);
        $pages[] = $this->page;
        $this->page = $this->manager->create($this->properties);
        $this->page->setTitle('Another page');
        $pages[] = $this->page;
        
        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
            ->andReturn($pages);
        $this->assertSame($pages, $this->manager->getAll(10, 0));
    }
    
    public function testGetById()
    {
        $this->page = $this->manager->create($this->properties);
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->page);
        $this->assertSame($this->page, $this->manager->getById(1));
    }

    public function testSave()
    {
        $this->page = $this->manager->create($this->properties);
        $this->em->setMethods(array('persist', 'flush'));
        $this->repository->shouldReceive('persist')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->save($this->page));
    }

    public function testRemove()
    {
        $this->page = $this->manager->create($this->properties);
        $this->em->setMethods(array('remove', 'flush'));
        $this->repository->shouldReceive('remove')->andReturn(true);
        $this->repository->shouldReceive('flush')->andReturn(true);
        $this->assertSame(null, $this->manager->remove($this->page));
    }
}
