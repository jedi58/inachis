<?php

namespace App\Tests\phpunit\Entity;

use App\Entity\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    protected $tag;

    public function setUp() : void
    {
        $this->tag = new Tag();

        parent::setUp();
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
