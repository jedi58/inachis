<?php
/**
 * @group unit
 */
use Inachis\Component\CoreBundle\Entity\Tag;

class TagTest extends PHPUnit_Framework_TestCase
{
    protected $tag;
    
    protected $properties = array();
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {        
        $this->tag = new Tag();
        $this->properties = array(
            'id' => 'UUID',
            'title' => 'awesome-tag'
        );
    }
    
    private function initialiseDefaultObject()
    {
        $this->tag->setId($this->properties['id']);
        $this->tag->setTitle($this->properties['title']);
    }
    
    public function testSettingOfObjectProperties()
    {
        $this->initialiseDefaultObject();
        $this->assertEquals($this->properties['id'],
                            $this->tag->getId());
        $this->assertEquals($this->properties['title'],
                            $this->tag->getTitle());
    }
}