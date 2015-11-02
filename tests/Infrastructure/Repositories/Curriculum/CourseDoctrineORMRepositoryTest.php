<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 2/11/15
 * Time: 08:55
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
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

    /**
     * @test
     * @group courserepo
     */
    public function should_return_course_of_id()
    {
        $tmp = $this->courseRepo->courseOfName(new Name('wiskunde'));
        $id = $tmp->id();
        $this->em->clear();

        $course = $this->courseRepo->courseOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Course', $course);
        $this->assertTrue($tmp->name()->equals($course->name()));
    }

    /**
     * @test
     * @group courserepo
     */
    public function should_return_null_when_id_not_found()
    {
        $id = 9999999;
        $course = $this->courseRepo->courseOfId($id);
        $this->assertNull($course);
    }

    /**
     * @test
     * @group courserepo
     */
    public function should_return_course_of_name()
    {
        $name = new Name('wiskunde');
        $course = $this->courseRepo->courseOfName($name);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Course', $course);
        $this->assertTrue($name->equals($course->name()));
    }

    /**
     * @test
     * @group courserepo
     */
    public function should_return_nulll_when_name_not_found()
    {
        $name = new Name('foo');
        $course = $this->courseRepo->courseOfName($name);
        $this->assertNull($course);
    }
}
