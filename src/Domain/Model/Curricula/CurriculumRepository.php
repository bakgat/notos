<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 23:27
 */

namespace Bakgat\Notos\Domain\Model\Curricula;



use Bakgat\Notos\Domain\Model\Identity\Group;

interface CurriculumRepository
{
    /**
     * Get all objectives in a curriculum.
     *
     * @param Curriculum $curriculum
     * @return mixed
     */
    public function objectivesOfCurriculum(Curriculum $curriculum);


    /**
     * @param Curriculum $curriculum
     * @return Curriculum
     */
    public function add(Curriculum $curriculum);

    /**
     * @param Structure $structure
     * @param $curriculumId
     * @return mix
     * @throws CurriculumNotFoundException
     */
    public function addStructure(Structure $structure, $curriculumId);

    /**
     * @param Objective $objective
     * @param $structureId
     * @return mixed
     * @throws StructureNotFoundException
     */
    public function addObjective(Objective $objective, $structureId);

    /**
     * @param Objective $objective
     * @param Group $group
     * @param $level
     * @return mixed
     *
     */
    public function addObjectiveLevel(Objective $objective, Group $group, $level);

    /**
     * Find the latest active curriculum by it's course name
     *
     * @param Course $course
     * @return Curriculum
     */
    public function curriculumOfCourse(Course $course);

    /**
     * Returns an objective that as a given code.
     *
     * @param $code
     * @return Objective
     */
    public function objectiveOfCode($code);

    /**
     * Finds an objective by it's id
     *
     * @param $id
     * @return Objective
     */
    public function objectiveOfId($id);

    /**
     * @param Curriculum $curriculum
     * @param $parent_id
     * @param $name
     * @param $type
     * @return Structure
     */
    public function structure(Curriculum $curriculum, $parent_id, $name, $type);
}