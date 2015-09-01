<?php
/**
 * @group unit
 */
class URLTest extends PHPUnit_Framework_TestCase
{
    protected $url;
    
    public function __construct()
    {        
        $this->url = new Inachis\Core\URL();
    }
   
    public function testInvalidURL()
    {
        $this->url->__set('link', 'test\"invalid\/URL');
        $this->assertEquals(false, $this->url->validateURL());
        
        $this->url->__set('link', 'test invalid URL');
        $this->assertEquals(false, $this->url->validateURL());
    }
    
    public function testValidURL()
    {
        $this->url->__set('link', 'test-valid-url');
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
}
