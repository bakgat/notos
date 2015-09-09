<?php
use Doctrine\ORM\Tools\Setup;

require_once __DIR__ . "/../vendor/autoload.php";
// Create a simple "default" Doctrine ORM configuration for XML Mapping
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/../src/Domain/Model"), $isDevMode, null, null, false);
// or if you prefer yaml or annotations
//$config = Setup::createXMLMetadataConfiguration(array(__DIR__."/config/xml"), $isDevMode);
//$config = Setup::createYAMLMetadataConfiguration(array(__DIR__."/config/yaml"), $isDevMode);
// database configuration parameters
$conn = [
    'driver' => 'mysqli',
    'host' => 'localhost',
    'dbname' =>'notosplus',
    'user' => 'root',
    'password' => '',
    'prefix' => ''
];
// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);