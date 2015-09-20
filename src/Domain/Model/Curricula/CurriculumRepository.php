<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 23:27
 */

namespace Bakgat\Notos\Domain\Model\Curricula;


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
     * @return mixed
     */
    public function add(Curriculum $curriculum);

    /**
     * @param Structure $structure
     * @param $curriculumId
     * @return mixed
     */
    public function addStructure(Structure $structure, $curriculumId);

    /**
     * @param Objective $objective
     * @param $structureId
     * @return mixed
     */
    public function addObjective(Objective $objective, $structureId);

    /**
     * @param Objective $objective
     * @param Group $group
     * @param $level
     * @return mixed
     */
    public function addObjectiveLevel(Objective $objective, Group $group, $level);

    /**
     * Find the latest active curriculum by it's course name
     *
     * @param Course $course
     * @return mixed
     */
    public function curriculumOfCourse(Course $course);

    /**
     * Returns an objective that as a given code.
     *
     * @param $code
     * @return mixed
     */
    public function objectiveOfCode($code);
}