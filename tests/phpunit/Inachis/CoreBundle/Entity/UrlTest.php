<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\Url;
use Inachis\Component\CoreBundle\Entity\Page;
use Mockery;

/**
 * @Entity
 * @group unit
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{
    protected $properties = array();
    protected $url;
    
    public function setUp()
    {
        $this->properties = array(
            'id' => 'UUID',
            'content' => new Page(),
            'link' => 'phpunit-test',
            'default' => true,
            'createDate' => new \DateTime('yesterday'),
            'modDate' => new \DateTime('now')
         );
        $this->url = new Url(new Page());
    }
    
    private function initialiseDefaultObject()
    {
        $this->url->setId($this->properties['id']);
        $this->url->setContent($this->properties['content']);
        $this->url->setLink($this->properties['link']);
        $this->url->setDefault($this->properties['default']);
        $this->url->setCreateDate($this->properties['createDate']);
        $this->url->setModDate($this->properties['modDate']);
    }
    
    public function testSettingOfObjectProperties()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(
            $this->properties['id'],
            $this->url->getId()
        );
        $this->assertEquals(
            $this->properties['content'],
            $this->url->getContent()
        );
        $this->assertEquals(
            $this->properties['link'],
            $this->url->getLink()
        );
        $this->assertEquals(
            $this->properties['default'],
            $this->url->getDefault()
        );
        $this->assertEquals(
            $this->properties['createDate'],
            $this->url->getCreateDate()
        );
        $this->assertEquals(
            $this->properties['modDate'],
            $this->url->getModDate()
        );
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

    public function testSetModDateToNow()
    {
        $now = (new \DateTime('now'))->format('Y-m-d H:i:s');
        $this->url->setModDateToNow();
        $this->assertGreaterThanOrEqual($now, $this->url->getModDate());
    }
}
