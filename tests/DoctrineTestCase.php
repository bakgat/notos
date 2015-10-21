<?php

namespace Bakgat\Notos\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;


abstract class DoctrineTestCase extends TestCase
{
    /** @var  MockInterface */
    protected $em;
    /** @var  MockInterface */
    protected $doctrine;

    public function setUp()
    {
        parent::setUp();

        $this->em = $this->getMock('EntityManager', array('persist', 'flush'));
        $this->em
            ->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(true));
        $this->em
            ->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(true));
        $this->doctrine = $this->getMock('Doctrine', array('getEntityManager'));
        $this->doctrine
            ->expects($this->any())
            ->method('getEntityManager')
            ->will($this->returnValue($this->em));
    }

    public function tearDown()
    {
        $this->doctrine = null;
        $this->em = null;
    }
}