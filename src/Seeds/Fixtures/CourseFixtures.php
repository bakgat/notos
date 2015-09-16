<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 22:02
 */

namespace Bakgat\Notos\Seeds\Fixtures;


use Bakgat\Notos\Domain\Model\Curricula\Course;
use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Domain\Model\Curricula\Curriculum;
use Bakgat\Notos\Domain\Model\Curricula\Objective;
use Bakgat\Notos\Domain\Model\Curricula\ObjectiveControlLevel;
use Bakgat\Notos\Domain\Model\Curricula\Structure;
use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Infrastructure\Repositories\Curriculum\CourseDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\GroupDoctrineORMRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CourseFixtures implements FixtureInterface
{
    /** @var CourseRepository $courseRepo */
    private $courseRepo;
    /** @var GroupRepository $groupRepo */
    private $groupRepo;
    /** @var ObjectManager $manager */
    private $manager;

    private $cur_classlevels = ['JK', 'OK', 'L1', 'L2', 'L3', 'L4', 'L5', 'L6'];

    private $math_objectives = [];

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        $this->manager = $manager;
        $this->courseRepo = new CourseDoctrineORMRepository($manager);
        $this->groupRepo = new GroupDoctrineORMRepository($manager);

        $this->math_objectives = include_once __DIR__ . '/objectives_math.php';
        $this->createGroups();
        $this->createCourses();
        $this->createCurricula();

    }

    private function createGroups()
    {
        foreach ($this->cur_classlevels as $grouplevel) {
            if (!$this->groupRepo->groupOfName($grouplevel)) {
                $group = Group::register(new Name($grouplevel));
                $this->manager->persist($group);
            }
        }
        $this->manager->flush();
    }

    private function createCourses()
    {
        $maths = Course::register(new Name('wiskunde'));
        $this->manager->persist($maths);
        $this->manager->flush();
    }

    private function createCurricula()
    {
        $maths = $this->courseRepo->courseOfName(new Name('wiskunde'));
        $maths_cur = new Curriculum($maths, 2000);
        $maths_cur->setCode('D/2000/0938/02');
        $this->manager->persist($maths_cur);

        $this->recursiveStructure($this->math_objectives, null, $maths_cur);

        $this->manager->flush();
    }

    /*
     * Recursive functions for adding structure / objectives in curricula
     */
    private function recursiveStructure($structure, $parent, $curr)
    {
        foreach ($structure as $struc) {
            $data = $struc;

            $structure = Structure::register($curr, new Name($data['name']), $data['kind']);
            if ($parent) {
                $parent->addChild($structure);
                $structure->setParent($parent);
            }
            $this->manager->persist($structure);
            $curr->addStructure($structure);


            //$s = $this->curriculum->createStructure($curr, $data);

            if (isset($data['obj'])) {
                $this->recursiveObjectives($data['obj'], null, $structure);
            }
            if (isset($data['children'])) {
                $this->recursiveStructure($data['children'], $structure, $curr);
            }
        }
        $this->manager->persist($curr);
        if ($parent) {
            $this->manager->persist($parent);
        }
    }

    private function recursiveObjectives($objectives, $parent, $structure)
    {
        foreach ($objectives as $obj) {
            $data = $obj;
            //$data['parent'] = $parent;

            $objective = Objective::register(new Name($data['name']), $data['code'], $structure);
            if ($parent) {
                $parent->addChild($objective);
                $objective->setParent($parent);
            }

            $this->manager->persist($objective);

            $structure->addObjective($objective);

            if (isset($data['level'])) {
                $o_levels = str_split($data['level']);

                //find group
                $i = 0;
                foreach ($this->cur_classlevels as $grouplevel) {
                    $group = $this->groupRepo->groupOfName($grouplevel);
                    $obj_level = ObjectiveControlLevel::register($group, $objective, $o_levels[$i++]);
                    $this->manager->merge($obj_level);
                }
            }

            if (isset($data['obj'])) {
                $this->recursiveObjectives($data['obj'], $objective, $structure);
            }
        }
        $this->manager->persist($structure);
        if ($parent) {
            $this->manager->persist($parent);
        }
    }

}