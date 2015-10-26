<?php

namespace Inachis\Tests\Core;

use Inachis\Core\Url;
use Mockery;
/**
 * @Entity
 * @group unit
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{
    protected $url;
    protected $properties = array();
    
    public $em;
    public $repository;
    
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
            'default' => true
        );
        $this->url = new Url($this->em);
    }
    
    private function initialiseDefaultObject()
    {
        $this->url->setId($this->properties['id']);
        $this->url->setContentType($this->properties['content_type']);
        $this->url->setContentId($this->properties['content_id']);
        $this->url->setLink($this->properties['link']);
        $this->url->setDefault($this->properties['default']);
    }

    public function testSettingOfObjectProperties()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals($this->properties['id'],
                            $this->url->getId());
        $this->assertEquals($this->properties['content_type'],
                            $this->url->getContentType());
        $this->assertEquals($this->properties['content_id'],
                            $this->url->getContentId());
        $this->assertEquals($this->properties['link'],
                            $this->url->getLink());
        $this->assertEquals($this->properties['default'],
                            $this->url->getDefault());
    }
   
    public function testInvalidURL()
    {
        $this->url->setLink('test\"invalid\/URL');
        $this->assertEquals(false, $this->url->validateURL());
        
        $this->url->setLink('test invalid URL');
        $this->assertEquals(false, $this->url->validateURL());
    }
    
    public function testValidURL()
    {
        $this->url->setLink('test-valid-url');
        $this->assertEquals(true, $this->url->validateURL());
    }
    
    public function testConvertBasicURL()
    {
        $this->assertEquals('test', $this->url->urlify('Test'));
    }
    
    public function testConvertURLWithSpaces()
    {
        $this->assertEquals('a-basic-title', $this->url->urlify('A Basic Title'));
    }
    
    public function testConvertURLWithTabs()
    {
        $this->assertEquals('a-basic-title', $this->url->urlify('A Basic Title'));
    }
    
    public function testConvertURLWithPunctuation()
    {
        $this->assertEquals('an-inachis-basic-title', 
                $this->url->urlify('An Inachis\' Basic Title'));
    }
    
    public function testConvertURLWithSizeLimit()
    {
        $this->assertEquals('an-inachis-basi', 
                $this->url->urlify('An Inachis\' Basic Title', 15));
    }
    
    public function testGetLinkFromURI()
    {
        $this->assertEquals('test-url', 
                $this->url->fromUri('https://www.test.com/2015/01/01/test-url/?debug#top'));
    }
    
    public function testGetLinkFromURIWithoutSchema()
    {
        $this->assertEquals('test-url', 
                $this->url->fromUri('www.test.com/2015/01/01/test-url/?debug#top'));
    }
    
    public function testGetLinkFromURIWithPathOnly()
    {
        $this->assertEquals('test-url', 
                $this->url->fromUri('test-url?debug#top'));
    }
}
