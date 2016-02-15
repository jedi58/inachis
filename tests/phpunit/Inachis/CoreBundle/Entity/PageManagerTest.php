<?php

namespace Inachis\Component\CoreBundle\Tests\Entity;

use Inachis\Component\CoreBundle\Entity\Page;
use Inachis\Component\CoreBundle\Entity\PageManager;
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
            'sub-title' => 'The first page',
            'content' => '<p>This is a test page.</p>',
            'author' => 'UUID',
            'feature_image' => 'UUID',
            'feature_snippet' => 'This is a short excerpt of the page',
            'status' => Page::DRAFT,
            'visibility' => Page::VIS_PUBLIC,
            'timezone' => 'UTC',
            'post_date' => '',
            'mod_date' => '',
            'password' => '',
            'allow_comments' => true
        );
        $this->manager = new PageManager($this->em);
        $this->page = $this->manager->create();
    }
    
    private function initialiseDefaultObject()
    {
        $this->page = $this->manager->create();
        $this->page->setId($this->properties['id']);
        $this->page->setTitle($this->properties['title']);
        $this->page->setSubTitle($this->properties['sub-title']);
        $this->page->setContent($this->properties['content']);
        $this->page->setAuthor($this->properties['author']);
        $this->page->setFeatureImage($this->properties['feature_image']);
        $this->page->setFeatureSnippet($this->properties['feature_snippet']);
        $this->page->setStatus($this->properties['status']);
        $this->page->setVisibility($this->properties['visibility']);
        $this->page->setTimezone($this->properties['timezone']);
        $this->page->setPostDate($this->properties['post_date']);
        $this->page->setModDate($this->properties['mod_date']);
        $this->page->setPassword($this->properties['password']);
        $this->page->setAllowComments($this->properties['allow_comments']);
    }
    
    public function testGetAll()
    {
        $pages = array();
        $this->initialiseDefaultObject();
        $pages[] = $this->page;
        $this->initialiseDefaultObject();
        $this->page->setTitle('Another page');
        $pages[] = $this->page;
        
        $this->repository->shouldReceive('findBy')->with(array(), array(), 10, 0)
                ->andReturn($pages);
        $this->assertSame($pages, $this->manager->getAll(10, 0));
    }
    
    public function testGetById()
    {
        $this->page = $this->manager->create();
        $this->repository->shouldReceive('find')->with(1)->andReturn($this->page);
        $this->assertSame($this->page, $this->manager->getById(1));
    }
}
