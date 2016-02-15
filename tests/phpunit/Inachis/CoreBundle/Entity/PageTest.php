<?php

namespace Inachis\Component\CoreBundle\Tests\Entity;

/**
 * @group unit
 */
use Inachis\Component\CoreBundle\Entity\Page;

class PageTest extends PHPUnit_Framework_TestCase
{
    protected $page;
    protected $properties = array();
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->page = new Page();
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
            'post_date' => $this->page->setPostDateFromDateTime(new \DateTime('yesterday noon')),
            'mod_date' => $this->page->setModDateFromDateTime(new \DateTime('now')),
            'password' => '',
            'allow_comments' => true
        );
    }
    
    private function initialiseDefaultObject()
    {
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
    
    public function testSettingOfObjectProperties()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(
            $this->properties['id'],
            $this->page->getId()
        );
        $this->assertEquals(
            $this->properties['title'],
            $this->page->getTitle()
        );
        $this->assertEquals(
            $this->properties['sub-title'],
            $this->page->getSubTitle()
        );
        $this->assertEquals(
            $this->properties['content'],
            $this->page->getContent()
        );
        $this->assertEquals(
            $this->properties['author'],
            $this->page->getAuthor()
        );
        $this->assertEquals(
            $this->properties['feature_image'],
            $this->page->getFeatureImage()
        );
        $this->assertEquals(
            $this->properties['feature_snippet'],
            $this->page->getFeatureSnippet()
        );
        $this->assertEquals(
            $this->properties['status'],
            $this->page->getStatus()
        );
        $this->assertEquals(
            $this->properties['visibility'],
            $this->page->getVisibility()
        );
        $this->assertEquals(
            $this->properties['timezone'],
            $this->page->getTimezone()
        );
        $this->assertEquals(
            $this->properties['post_date'],
            $this->page->getPostDate()
        );
        $this->assertEquals(
            $this->properties['mod_date'],
            $this->page->getModDate()
        );
        $this->assertEquals(
            $this->properties['password'],
            $this->page->getPassword()
        );
        $this->assertEquals(
            $this->properties['allow_comments'],
            $this->page->getAllowComments()
        );
    }
    
    public function testValidStatus()
    {
        $this->assertEquals(true, $this->page->isValidStatus(Page::DRAFT));
    }
    
    public function testInvalidStatus()
    {
        $this->assertEquals(false, $this->page->isValidStatus('Moo'));
    }
    
    public function testValidTimezone()
    {
        $this->assertEquals(true, $this->page->isValidTimezone('Europe/London'));
    }
    
    public function testInvalidTimezone()
    {
        $this->assertEquals(false, $this->page->isValidTimezone('Mars/Phobos'));
    }
    
    public function testIsScheduledPage()
    {
        $this->initialiseDefaultObject();
        $this->page->setPostDateFromDateTime(new \DateTime('tomorrow noon'));
        $this->assertEquals(true, $this->page->isScheduledPage());
    }
    
    public function testIsNotScheduledPage()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(false, $this->page->isScheduledPage());
    }
}
