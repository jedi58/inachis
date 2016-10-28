<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\Category;
use Inachis\Component\CoreBundle\Entity\Page;
use Inachis\Component\CoreBundle\Entity\Tag;
use Inachis\Component\CoreBundle\Entity\Url;
use Inachis\Component\CoreBundle\Entity\User;

/**
 * @group unit
 */
class PageTest extends \PHPUnit_Framework_TestCase
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
            'author' => new User(),
            'feature_image' => 'UUID',
            'feature_snippet' => 'This is a short excerpt of the page',
            'status' => Page::DRAFT,
            'visibility' => Page::VIS_PUBLIC,
            'timezone' => 'UTC',
            'create_date' => new \DateTime('yesterday noon'),
            'post_date' => new \DateTime('yesterday noon'),
            'mod_date' => new \DateTime('now'),
            'password' => '',
            'allow_comments' => true,
            'type' => 'post',
            'latlong' => '0.1,0,2',
            'sharingMessage' => 'This should be less than 140 characters'
        );
        $url = new Url($this->page);
        $url->setLink('test');
        $this->page->addUrl($url);
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
        $this->page->setCreateDate($this->properties['create_date']);
        $this->page->setPostDate($this->properties['post_date']);
        $this->page->setModDate($this->properties['mod_date']);
        $this->page->setPassword($this->properties['password']);
        $this->page->setAllowComments($this->properties['allow_comments']);
        $this->page->setType($this->properties['type']);
        $this->page->setLatlong($this->properties['latlong']);
        $this->page->setSharingMessage($this->properties['sharingMessage']);
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
            $this->properties['create_date']->format('Y-m-d H:i:s'),
            $this->page->getCreateDate()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            $this->properties['post_date']->format('Y-m-d H:i:s'),
            $this->page->getPostDate()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            $this->properties['mod_date']->format('Y-m-d H:i:s'),
            $this->page->getModDate()->format('Y-m-d H:i:s')
        );
        $this->assertEquals(
            $this->properties['password'],
            $this->page->getPassword()
        );
        $this->assertEquals(
            $this->properties['allow_comments'],
            $this->page->isAllowComments()
        );
        $this->assertEquals(
            $this->properties['type'],
            $this->page->getType()
        );
        $this->assertEquals(
            $this->properties['latlong'],
            $this->page->getLatlong()
        );
        $this->assertEquals(
            $this->properties['sharingMessage'],
            $this->page->getSharingMessage()
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
        $this->page->setPostDate(new \DateTime('tomorrow noon'));
        $this->assertEquals(true, $this->page->isScheduledPage());
    }
    
    public function testIsNotScheduledPage()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals(false, $this->page->isScheduledPage());
    }

    public function testSetTypeInvalid()
    {
        try {
            $this->page->setType('something-bad');
        } catch (\Exception $exception) {
            $this->assertContains('not a valid page type', $exception->getMessage());
        }
    }

    public function testIsDraft()
    {
        $this->page->setStatus(Page::DRAFT);
        $this->assertEquals(Page::DRAFT, $this->page->getStatus());
    }

    public function testGetPostDateAsLink()
    {
        $this->page->setPostDate(new \DateTime('2016-12-25'));
        $this->assertEquals('2016/12/25/', $this->page->getPostDateAsLink());
    }

    public function testGetCategories()
    {
        $this->page->addCategory(new Category());
        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $this->page->getCategories()
        );
        $this->assertNotEmpty($this->page->getCategories());
    }

    public function testGetUrls()
    {
        $this->assertArrayHasKey(0, $this->page->getUrls());
    }

    public function testGetUrl()
    {
        $this->assertInstanceOf(
            'Inachis\Component\CoreBundle\Entity\Url',
            $this->page->getUrl(0)
        );
    }

    public function testGetUrlException()
    {
        try {
            $this->assertInstanceOf(
                'Inachis\Component\CoreBundle\Entity\Url',
                $this->page->getUrl(1)
            );
        } catch (\InvalidArgumentException $exception) {
            $this->assertContains('does not exist', $exception->getMessage());
        }
    }

    public function testGetTags()
    {
        $this->page->addTag(new Tag());
        $this->assertInstanceOf(
            'Doctrine\Common\Collections\ArrayCollection',
            $this->page->getTags()
        );
        $this->assertNotEmpty($this->page->getTags());
    }
}
