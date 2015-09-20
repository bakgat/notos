<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 20/09/15
 * Time: 21:04
 */

namespace Bakgat\Notos\Http\Controllers\Identity;


use Bakgat\Notos\Domain\Services\Identity\GroupService;
use Bakgat\Notos\Http\Controllers\Controller;

class GroupController extends Controller
{
    private $groupService;

    public function __construct(GroupService $groupService)
    {
        parent::__construct();
        //$this->middleware('auth');
        $this->groupService = $groupService;
    }

    public function indexLevels()
    {
        return $this->jsonResponse($this->groupService->groupsOfKind('level'), ['list']);
    }
}