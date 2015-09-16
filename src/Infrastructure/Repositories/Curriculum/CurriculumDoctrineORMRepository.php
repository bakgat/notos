<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 23:27
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\Curriculum;
use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Curricula\Group;
use Bakgat\Notos\Domain\Model\Curricula\Objective;
use Bakgat\Notos\Domain\Model\Curricula\Structure;

class CurriculumDoctrineORMRepository implements CurriculumRepository
{

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
}