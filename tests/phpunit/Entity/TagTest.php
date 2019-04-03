<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    protected $tag;

    public function setUp()
    {
        $this->tag = new Tag();
    }

    public function testSetAndGetId()
    {
        $this->tag->setId('test');
        $this->assertEquals('test', $this->tag->getId());
    }

    public function testSetAndGetTitle()
    {
        $this->tag->setTitle('test');
        $this->assertEquals('test', $this->tag->getTitle());
    }
}
