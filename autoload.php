<?php
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;


require_once __DIR__ . "/vendor/autoload.php";

// Bootstrap the JMS custom annotations for Object to Json mapping
\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'JMS\Serializer\Annotation',
    __DIR__ . '/vendor/jms/serializer/src'
);

$isDevMode = true;


/*$conn = [
    'driver' => 'mysqli',
    'host' => '127.0.0.1',
    'dbname' => 'notos_test',
    'user' => 'root',
    'password' => 'root',
    'prefix' => ''
];*/
$conn = [
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/notostest',
];
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src/Domain/Model"), $isDevMode, null, null, false);


// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
$connection = $entityManager->getConnection();

$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
$metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
