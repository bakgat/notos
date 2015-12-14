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
use Illuminate\Http\Request;

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

    public function indexClassgroups($orgId)
    {
        return $this->jsonResponse($this->groupService->groupsOfKind('classgroup', $orgId), ['list']);
    }

    public function store(Request $request, $orgId)
    {
        $group = $this->groupService->add($orgId, $request->all());
        return $this->jsonResponse($group, ['detail']);
    }

    public function update(Request $request, $orgId, $id)
    {
        $group = $this->groupService->update($id, $request->all());
        return $this->jsonResponse($group, ['detail']);
    }
}