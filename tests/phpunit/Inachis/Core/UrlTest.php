<?php
/**
 * @group unit
 */
class UrlTest extends PHPUnit_Framework_TestCase
{
    protected $url;
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {        
        $this->url = new Inachis\Core\Url();
        //parent::__construct($name, $data, $dataName);
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
    
    public function testGetLinkFromURI()
    {
        $this->assertEquals('test-url', 
                $this->url->fromUri('https://www.test.com/2015/01/01/test-url/?debug#top'));
    }
}
