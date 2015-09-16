<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 22:31
 */

namespace Bakgat\Notos\Seeds;


use Bakgat\Notos\Seeds\Fixtures\CourseFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\App;

class Seed
{


    /** @var  EntityManager */
    protected $em;
    /** @var  ORMExecutor */
    protected $executor;
    /** @var  Loader */
    protected $loader;

    public function __construct() {
        $this->em = App::make(\Doctrine\ORM\EntityManager::class);

        $this->executor = new ORMExecutor($this->em, new ORMPurger);
        $this->loader = new Loader;

        $this->loader->addFixture(new CourseFixtures);
    }

    public function SeedAll() {
        $this->executor->execute($this->loader->getFixtures());
    }
}