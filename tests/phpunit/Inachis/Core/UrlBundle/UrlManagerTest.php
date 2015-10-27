
<?php

namespace Inachis\Tests\Core\UrlBundle;

use Inachis\Core\UrlBundle\UrlManager;
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
            'content_type' => 'Page',
            'content_id' => 'UUID',
            'link' => 'phpunit-test',
            'default' => true,
            'create_date' => new \DateTime('now')
        );
        $this->manager = new UrlManager($this->em);
        $this->url = $this->manager->create();
    }
    
    private function initialiseDefaultObject()
    {
        $this->url->setId($this->properties['id']);
        $this->url->setContentType($this->properties['content_type']);
        $this->url->setContentId($this->properties['content_id']);
        $this->url->setLink($this->properties['link']);
        $this->url->setDefault($this->properties['default']);
    }
    
    public function testGetAllByContentTypeAndId()
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
}
