<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/09/15
 * Time: 11:57
 */

namespace Bakgat\Notos\Http\Controllers\Curricula;


use Bakgat\Notos\Domain\Services\Curricula\CurriculumService;
use Bakgat\Notos\Http\Controllers\Controller;

class CurriculumController extends Controller
{
    /** @var CurriculumService $currService */
    private $currService;

    public function __construct(CurriculumService $curriculumService)
    {
        parent::__construct();
        $this->currService = $curriculumService;
    }

    public function indexObjectives($coursename)
    {
        $result = $this->currService->objectivesOfCurriculum($coursename);
        return $this->jsonResponse($result, ['list']);
    }
}