<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 2/11/15
 * Time: 08:55
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CourseDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class CourseDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var CourseRepository $courseRepo */
    private $courseRepo;

    public function setUp()
    {
        parent::setUp();
        $this->courseRepo = new CourseDoctrineORMRepository($this->em);
        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group courserepo
     */
    public function should_return_4_courses()
    {
        $courses = $this->courseRepo->all();
        $this->assertCount(4, $courses);
    }
}
