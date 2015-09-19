<?php
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . "/../vendor/autoload.php";

// Bootstrap the JMS custom annotations for Object to Json mapping
// Bootstrap the JMS custom annotations for Object to Json mapping
\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'JMS\Serializer\Annotation',
    __DIR__ . '/../vendor/jms/serializer/src'
);
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/../src/Domain/Model"), $isDevMode, null, null, false);

$conn = [
    'driver' => 'mysqli',
    'host' => '127.0.0.1',
    'dbname' =>'notosplus',
    'user' => 'root',
    'password' => 'root',
    'prefix' => ''
];
// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);