<?php

namespace Bakgat\Notos\Tests;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;


abstract class DoctrineTestCase extends TestCase
{
    /** @var  EntityManager */
    protected $em;
    /** @var  ORMExecutor */
    protected $executor;
    /** @var  Loader */
    protected $loader;


    public function setUp()
    {
        parent::setUp();
        $this->em = $this->app->make(\Doctrine\ORM\EntityManager::class);
        $this->executor = new ORMExecutor($this->em, new ORMPurger);
        $this->loader = new Loader;


        $this->loader->addFixture(new TestFixtures);

        $this->em->beginTransaction();

    }

    public function tearDown()
    {
        if ($this->em) {
            $this->em->rollback();
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            \Bakgat\Notos\NotosServiceProvider::class,
        ];

    }
}