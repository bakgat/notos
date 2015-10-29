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


    public function setUp() {
        parent::setUp();
        $this->em = $this->app->make(\Doctrine\ORM\EntityManager::class);
        $this->executor = new ORMExecutor($this->em, new ORMPurger);
        $this->loader = new Loader;

        $this->em->beginTransaction();

    }
    public function tearDown() {
        $this->em->rollback();
    }

    /*public static function tearDownAfterClass()
    {

        $metadataNamespace = 'Bakgat\\Notos\\Domain\\Model\\';
        $resetTables = [
        'Identity\Party',
        'Identity\User',
        'Identity\Group',
        'Identity\Organization',
        'Resource\Resource',
        'Resource\Asset',
        'Resource\Book',
        'Resource\Image',
        'Location\Location',
        'Location\Blog',
        'Location\Website',
        'Relations\Relation',
        'Relations\PartyRelation',
        'ACL\Role',
        'ACL\UserRole',
        'ACL\Permission',
        'Curricula\Course',
        'Curricula\Curriculum',
        'Curricula\Objective',
        'Curricula\ObjectiveControlLevel',
        'Curricula\Structure',
        'Kind'
    ];

        $em = App::make(\Doctrine\ORM\EntityManager::class);
        $connection = self::$em->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();

        foreach ($resetTables as $name) {
            $className = $metadataNamespace . $name;

            $cmd = $em->getClassMetadata($className);
            $connection->beginTransaction();
            try {
                $connection->query('SET FOREIGN_KEY_CHECKS=0');
                $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
                $connection->executeUpdate($q);
                $connection->query('SET FOREIGN_KEY_CHECKS=1');
                $connection->exec('ALTER TABLE ' . $cmd->getTableName() . ' AUTO_INCREMENT = 1;');
                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollback();
            }
        }
    }
*/
    protected function getPackageProviders($app)
    {
        return [
            \Bakgat\Notos\NotosServiceProvider::class,
        ];

    }
}