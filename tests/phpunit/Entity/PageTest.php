<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\Page;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase
{
    protected $page;

    public function setUp()
    {
        $this->page = new Page();
    }

    public function testSetAndGetLatlong()
    {
        $this->page->setLatlong('100,100');
        $this->assertEquals('100,100', $this->page->getLatlong());
    }

    public function testIsDraft()
    {
        $this->page->setStatus(Page::DRAFT);
        $this->assertTrue($this->page->isDraft());
        $this->page->setStatus(Page::PUBLISHED);
        $this->assertFalse($this->page->isDraft());
    }

    public function testSetAndGetContent()
    {
        $this->page->setContent('test');
        $this->assertEquals('test', $this->page->getContent());
    }

    public function testSetAndGetTimezone()
    {

    }

    public function testSetAndGetFeatureImage()
    {

    }

    public function testSetAndGetTags()
    {

    }

    public function testSetAndGetUrls()
    {

    }

    public function testSetAndGetPassword()
    {
        $this->page->setPassword('test');
        $this->assertEquals('test', $this->page->getPassword());
    }

    public function testIsValidTimezone()
    {

    }

    public function testIsValidVisibility()
    {

    }

    public function testSetAndGetTitle()
    {
        $this->page->setTitle('test');
        $this->assertEquals('test', $this->page->getTitle());
    }

    public function testSetAndGetCreateDate()
    {

    }

    public function testIsScheduledPage()
    {

    }

    public function testSetAndGetVisibility()
    {

    }

    public function testSetAndGetModDate()
    {

    }

    public function testIsAllowComments()
    {

    }

    public function testSetAndGetType()
    {

    }

    public function testSetAndGetSharingMessage()
    {

    }

    public function testSetAndGetSubTitle()
    {

    }

    public function testSetAndGetId()
    {
        $this->page->setId('test');
        $this->assertEquals('test', $this->page->getId());
    }

    public function testSetAndGetStatus()
    {
        $this->page->setStatus(Page::DRAFT);
        $this->assertEquals(Page::DRAFT, $this->page->getStatus());
    }

    public function testSetAndGetCategories()
    {

    }

    public function testSetAndGetFeatureSnippet()
    {
            $this->page->setFeatureSnippet('test');
            $this->assertEquals('test', $this->page->getFeatureSnippet());
    }

    public function testIsValidStatus()
    {
        $this->assertFalse($this->page->isValidStatus('moo'));
        $this->assertTrue($this->page->isValidStatus(Page::DRAFT));
        $this->assertTrue($this->page->isValidStatus(Page::PUBLISHED));
    }

    public function testSetAndGetAuthor()
    {

    }

    public function testSetAndGetPostDate()
    {

    }

    public function testSetAndGetUrl()
    {

    }
}
