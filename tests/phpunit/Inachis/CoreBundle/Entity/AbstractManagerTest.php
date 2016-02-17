<?php

namespace Inachis\Component\CoreBundle\Tests\Entity;

use Inachis\Component\CoreBundle\Entity\AbstractManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Mockery;

/**
 * @group unit
 */
class AbstractManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    protected $em;
    /**
     *
     */
    protected $stub;
    /**
     *
     */
    public function setUp()
    {
        $this->em = Mockery::mock('Doctrine\ORM\EntityManager')
            ->shouldIgnoreMissing();
        $this->stub = Mockery::mock(
            'Inachis\Component\CoreBundle\Entity\AbstractManager',
            array($this->em)
        )->shouldAllowMockingProtectedMethods();
    }
    /**
     *
     */
    public function testGetClass()
    {
        $this->stub
            ->shouldReceive('getClass')
            ->andReturn('test-class')
            ->getMock();

        $this->assertEquals('test-class', $this->stub->getClass());
    }
    /**
     *
     */
    public function testGetRepository()
    {
        $this->stub
            ->shouldReceive('getRepository')
            ->andReturn(Mockery::mock('Doctrine\ORM\EntityRepository'))
            ->getMock();
        $this->assertEquals(
            Mockery::mock('Doctrine\ORM\EntityRepository'),
            $this->stub->getRepository()
        );
    }
}
