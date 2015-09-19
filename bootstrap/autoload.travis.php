<?php
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . "/../vendor/autoload.php";

// Bootstrap the JMS custom annotations for Object to Json mapping
\Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
    'Bakgat\Serializer\Annotation',
    dirname(__DIR__).'/vendor/bakgat/serializer/src'
);

$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/../src/Domain/Model"), $isDevMode, null, null, false);
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