<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 2/11/15
 * Time: 08:55
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class CourseDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var CourseRepository $courseRepo */
    private $courseRepo;

    public function setUp() {

        $this->executor->execute($this->loader->getFixtures());
    }
}
