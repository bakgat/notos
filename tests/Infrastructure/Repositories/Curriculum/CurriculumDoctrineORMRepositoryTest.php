<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 2/11/15
 * Time: 20:37
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Domain\Model\Curricula\Curriculum;
use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Curricula\Structure;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CourseDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CurriculumDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class CurriculumDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var CurriculumRepository $currRepo */
    private $currRepo;
    /** @var CourseRepository $courseRepo */
    private $courseRepo;

    public function setUp()
    {
        parent::setUp();

        $this->currRepo = new CurriculumDoctrineORMRepository($this->em);
        $this->courseRepo = new CourseDoctrineORMRepository($this->em);

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

        $this->assertCount(1, $curr->getStructures());
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
}
