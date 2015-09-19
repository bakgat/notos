<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/09/15
 * Time: 10:11
 */

namespace Bakgat\Notos\Domain\Services\Curricula;


use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Domain\Model\Curricula\CurriculumRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;

class CurriculumService
{
    /** @var CourseRepository $courseRepo */
    private $courseRepo;
    /** @var CurriculumRepository $currRepo  */
    private $currRepo;

    public function __construct(CourseRepository $courseRepository, CurriculumRepository $curriculumRepository) {
        $this->courseRepo = $courseRepository;
        $this->currRepo = $curriculumRepository;
    }


    public function objectivesOfCurriculum($courseName) {
        $course = $this->courseRepo->courseOfName(new Name($courseName));
        $curriculum = $this->currRepo->curriculumOfCourse($course);
        return $this->currRepo->objectivesOfCurriculum($curriculum);
    }
}