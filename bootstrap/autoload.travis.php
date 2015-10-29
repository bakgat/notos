<?php
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . "/vendor/autoload.php";

// Bootstrap the JMS custom annotations for Object to Json mapping
\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'JMS\Serializer\Annotation',
    __DIR__ . '/vendor/jms/serializer/src'
);

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src/Domain/Model"), $isDevMode, null, null, false);
// or if you prefer yaml or annotations
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);
// database configuration parameters
$conn = [
    'driver' => 'mysqli',
    'host' => '127.0.0.1',
    'dbname' =>'notosplus',
    'user' => 'travis',
    'password' => '',
    'prefix' => ''
];


// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
$connection = $entityManager->getConnection();

$schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
/*$metadatas = $entityManager->getMetadataFactory()->getAllMetadata();

$schemaTool->dropSchema($metadatas);
$schemaTool->createSchema($metadatas);*/
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

/*
 * CLEAN TABLES BEFORE START
 */
$connection = $entityManager->getConnection();
$dbPlatform = $connection->getDatabasePlatform();

foreach ($resetTables as $name) {
    $className = $metadataNamespace . $name;

    $cmd = $entityManager->getClassMetadata($className);
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