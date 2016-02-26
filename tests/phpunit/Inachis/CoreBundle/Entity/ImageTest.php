<?php

namespace Inachis\Tests\CoreBundle\Entity;

use Inachis\Component\CoreBundle\Entity\Image;

class ImageTest extends \PHPUnit_Framework_TestCase
{
    protected $image;
    
    protected $properties = array();
    
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->image = new Image();
        $this->properties = array(
            'id' => 'UUID',
            'title' => 'awesome-tag'
        );
    }
    
    private function initialiseDefaultObject()
    {
        //$this->tag->setId($this->properties['id']);
        //$this->tag->setTitle($this->properties['title']);
    }
    
    public function testSettingOfObjectProperties()
    {
        $this->initialiseDefaultObject();
var_dump($this->image);
        /*
        $this->assertEquals(
            $this->properties['id'],
            $this->image->getId()
        );
        $this->assertEquals(
            $this->properties['title'],
            $this->image->getTitle()
        );*/
    }
}

