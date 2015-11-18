<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/11/15
 * Time: 19:45
 */

namespace Bakgat\Notos\Tests;


use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

abstract class ControllersTestCase extends DoctrineTestCase
{
    /**
     * Default preparation for each test
     */
    public function setUp()
    {
        parent::setUp();
        \Doctrine\Common\Annotations\AnnotationRegistry::registerAutoloadNamespace(
            'JMS\Serializer\Annotation',
            __DIR__ . '/../vendor/jms/serializer/src'
        );
    }


}