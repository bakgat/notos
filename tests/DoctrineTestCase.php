<?php

namespace Bakgat\Notos\Tests;

use Bakgat\Notos\Tests\Fixtures\TestFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Illuminate\Support\Facades\App;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;

/**
 * @backupGlobals disabled
 */
abstract class DoctrineTestCase extends TestCase
{
    /** @var  EntityManager */
    protected $em;


    /**
     * Default preparation for each test
     */
    public function setUp()
    {
        parent::setUp();

        $this->prepareForTests();

        $this->em->beginTransaction();
    }

    public function tearDown()
    {
        if ($this->em) {
            $this->em->rollback();
        }
    }


    /**
     * Migrates the database and set the mailer to 'pretend'.
     * This will cause the tests to run quickly.
     */
    private function prepareForTests()
    {
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            __DIR__ . '/vendor/jms/serializer/src'
        );

        $this->em = $this->app->make(\Doctrine\ORM\EntityManager::class);

        $this->executor = new ORMExecutor($this->em, new ORMPurger);
        $this->loader = new Loader;

        //$this->loader->addFixture(new TestFixtures);
        //$this->executor->execute($this->loader->getFixtures());
    }

    protected function getPackageProviders($app)
    {
        return [
            \Bakgat\Notos\NotosServiceProvider::class,
        ];
    }

    /*

    public function setUp()
    {
        parent::setUp();

        $this->em = $this->app->make(\Doctrine\ORM\EntityManager::class);
        $this->executor = new ORMExecutor($this->em, new ORMPurger);
        $this->loader = new Loader;

        //$this->loader->addFixture(new TestFixtures);
        //$this->executor->execute($this->loader->getFixtures());

        //$this->em->beginTransaction();
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

    }*/
}