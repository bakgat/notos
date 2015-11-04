<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 2/11/15
 * Time: 20:37
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\Course;
use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Domain\Model\Curricula\Curriculum;
use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Curricula\Objective;
use Bakgat\Notos\Domain\Model\Curricula\Structure;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CourseDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CurriculumDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\GroupDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class CurriculumDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var CurriculumRepository $currRepo */
    private $currRepo;
    /** @var CourseRepository $courseRepo */
    private $courseRepo;
    /** @var GroupRepository $groupRepo */
    private $groupRepo;

    public function setUp()
    {
        parent::setUp();

        $this->currRepo = new CurriculumDoctrineORMRepository($this->em);
        $this->courseRepo = new CourseDoctrineORMRepository($this->em);
        $this->groupRepo = new GroupDoctrineORMRepository($this->em);

        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_add_curriculum()
    {
        $n_maths = new Name('nederlands');
        $year_published = 2010;
        $code = '2009/029/109AE';

        $course = $this->courseRepo->courseOfName($n_maths);

        $curr = new Curriculum($course, $year_published);
        $curr->setCode($code);

        $this->currRepo->add($curr);

        $this->em->clear();

        $curriculum = $this->currRepo->curriculumOfCourse($course);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Curriculum', $curriculum);
        $this->assertEquals($curr->yearPublished(), $curriculum->yearPublished());
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_add_structure_to_curriculum()
    {
        $n_maths = new Name('wiskunde');
        $course = $this->courseRepo->courseOfName($n_maths);
        $curr = $this->currRepo->curriculumOfCourse($course);
        $currId = $curr->id();

        $structure = new Structure();
        $structure->setName(new Name('structure'));
        $structure->setType('chapter');

        $this->currRepo->addStructure($structure, $currId);

        $this->assertCount(2, $curr->getStructures());
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Structure', $curr->getStructures()[0]);

        $this->em->clear();

        $result = $this->currRepo->structure($curr, null, 'structure', 'chapter');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Structure', $result);
        $this->assertTrue($result->name()->equals($structure->name()));
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_throw_error_when_no_curr_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Curricula\Exceptions\CurriculumNotFoundException');

        $structure = new Structure();
        $structure->setName(new Name('structure'));
        $structure->setType('chapter');

        $currId = 9999999;

        $this->currRepo->addStructure($structure, $currId);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_add_objective_to_structure()
    {
        $n_maths = new Name('wiskunde');
        $course = $this->courseRepo->courseOfName($n_maths);
        $curr = $this->currRepo->curriculumOfCourse($course);
        $chapter1 = $this->currRepo->structure($curr, null, 'chapter 1', 'chapter');
        $strucId = $chapter1->id();

        $n_obj = new Name('Doel 1');
        $c_obj = 'D0.1';
        $objective = Objective::register($n_obj, $c_obj, $chapter1);

        $this->currRepo->addObjective($objective, $strucId);

        $this->em->clear();

        $result = $this->currRepo->objectivesOfCurriculum($curr);
        $this->assertCount(2, $result);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Objective', $result[1]);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_throw_error_when_no_struc_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Curricula\Exceptions\StructureNotFoundException');
        $strucId = 9999999;

        $n_obj = new Name('Doel 1');
        $c_obj = 'D0.1';
        $objective = Objective::register($n_obj, $c_obj, new Structure());

        $this->currRepo->addObjective($objective, $strucId);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_add_objective_level()
    {
        $cg_K1A = $this->groupRepo->groupOfName('1KA');
        $objective = $this->currRepo->objectiveOfCode('D0.1.a');
        $level = 2;
        $this->currRepo->addObjectiveLevel($objective, $cg_K1A, $level);

        $this->em->clear();

        $result = $this->currRepo->objectiveOfCode('D0.1.a');
        $this->assertCount(2, $result->levels());
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\ObjectiveControlLevel', $result->levels()[1]);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_objectives_of_curriculum()
    {
        $n_maths = new Name('wiskunde');
        $course = $this->courseRepo->courseOfName($n_maths);
        $curr = $this->currRepo->curriculumOfCourse($course);

        $objectives = $this->currRepo->objectivesOfCurriculum($curr);
        $this->assertCount(1, $objectives);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Objective', $objectives[0]);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_empty_when_no_objectives_are_found()
    {
        $course = new Course(new Name('foo'));
        $curr = new Curriculum($course, 2015);

        $objectives = $this->currRepo->objectivesOfCurriculum($curr);
        $this->assertEmpty($objectives);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_curr_of_course()
    {
        $n_maths = new Name('wiskunde');
        $course = $this->courseRepo->courseOfName($n_maths);
        $curr = $this->currRepo->curriculumOfCourse($course);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Curriculum', $curr);
        $this->assertEquals(2009, $curr->yearPublished());
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_null_when_no_curr_found()
    {
        $course = new Course(new Name('foo'));
        $curr = $this->currRepo->curriculumOfCourse($course);

        $this->assertNull($curr);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_objective_of_code()
    {
        $code = 'D0.1.a';
        $objective = $this->currRepo->objectiveOfCode($code);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Objective', $objective);
        $this->assertEquals($code, $objective->code());
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_null_when_no_objective_of_code_found()
    {
        $code = 'D0.1';
        $objective = $this->currRepo->objectiveOfCode($code);

        $this->assertNull($objective);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_objective_of_id()
    {
        $code = 'D0.1.a';
        $tmp = $this->currRepo->objectiveOfCode($code);
        $id = $tmp->getId();

        $this->em->clear();

        $objective = $this->currRepo->objectiveOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Objective', $objective);
        $this->assertEquals($code, $objective->code());
        $this->assertTrue($tmp->name()->equals($objective->name()));
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_null_when_no_objective_of_id_found()
    {
        $id = 999999;

        $objective = $this->currRepo->objectiveOfId($id);

        $this->assertNull($objective);
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_structure()
    {
        $n_maths = new Name('wiskunde');
        $course = $this->courseRepo->courseOfName($n_maths);
        $curr = $this->currRepo->curriculumOfCourse($course);

        $n_struc = new Name('chapter 1');
        $structure = $this->currRepo->structure($curr, null, $n_struc->toString(), 'chapter');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Curricula\Structure', $structure);
        $this->assertTrue($n_struc->equals($structure->name()));
    }

    /**
     * @test
     * @group currrepo
     */
    public function should_return_null_when_no_structure_found()
    {
        $n_maths = new Name('wiskunde');
        $course = $this->courseRepo->courseOfName($n_maths);
        $curr = $this->currRepo->curriculumOfCourse($course);

        $n_struc = new Name('chapter 2');
        $structure = $this->currRepo->structure($curr, null, $n_struc->toString(), 'chapter');
        $this->assertNull($structure);
    }
}
