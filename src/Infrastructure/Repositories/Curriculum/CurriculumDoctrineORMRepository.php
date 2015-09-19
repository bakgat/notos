<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 23:27
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\Course;
use Bakgat\Notos\Domain\Model\Curricula\Curriculum;
use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Curricula\Group;
use Bakgat\Notos\Domain\Model\Curricula\Objective;
use Bakgat\Notos\Domain\Model\Curricula\Structure;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Cache;

class CurriculumDoctrineORMRepository implements CurriculumRepository
{

    /** @var EntityManager $em */
    private $em;
    /** @var string $class */
    private $class;
    /** @var string $currClass */
    private $currClass;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Curricula\Objective';
        $this->currClass = 'Bakgat\Notos\Domain\Model\Curricula\Curriculum';
    }

    /**
     * @param Curriculum $curriculum
     * @return mixed
     */
    public function add(Curriculum $curriculum)
    {
        // TODO: Implement add() method.
    }

    /**
     * @param Structure $structure
     * @param $curriculumId
     * @return mixed
     */
    public function addStructure(Structure $structure, $curriculumId)
    {
        // TODO: Implement addStructure() method.
    }

    /**
     * @param Objective $objective
     * @param $structureId
     * @return mixed
     */
    public function addObjective(Objective $objective, $structureId)
    {
        // TODO: Implement addObjective() method.
    }

    /**
     * @param Objective $objective
     * @param Group $group
     * @param $level
     * @return mixed
     */
    public function addObjectiveLevel(Objective $objective, Group $group, $level)
    {
        // TODO: Implement addObjectiveLevel() method.
    }

    /**
     * Get all objectives in a curriculum.
     *
     * @param Curriculum $curriculum
     * @return mixed
     */
    public function objectivesOfCurriculum(Curriculum $curriculum)
    {
        $key = md5('objectives.' . $curriculum->code());

        if(Cache::has($key)) {
            return Cache::get($key);
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from($this->class, 'o')
            ->join('o.structure', 's')
            ->join('s.curriculum', 'c')
            ->where(
                $qb->expr()->eq('c.id', '?1')
            )
            ->setParameter(1, $curriculum->id());

        $result = $qb->getQuery()->getResult();
        Cache::forever('$key', $result);
        return $result;
    }

    /**
     * Find the latest active curriculum by it's course name
     *
     * @param Course $course
     * @return mixed
     */
    public function curriculumOfCourse(Course $course)
    {

        $qb = $this->em->createQueryBuilder();
        $qb->select('c')
            ->from($this->currClass, 'c')
            ->where(
                $qb->expr()->eq('c.course', '?1')
            )
            ->setParameter(1, $course->id());

        return $qb->getQuery()->getSingleResult();
    }
}