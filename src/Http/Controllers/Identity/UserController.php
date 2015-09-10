<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 15:31
 */

namespace Bakgat\Notos\Http\Controllers\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Services\Identity\UserService;
use Bakgat\Notos\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use JMS\Serializer\SerializerBuilder;

class UserController extends Controller
{

    /** @var UserService $userService */
    private $userService;

    public function __construct(UserService $userService)
    {

        parent::__construct();
        $this->middleware('auth');
        $this->userService = $userService;
    }

    public function index($orgId)
    {
        return $this->json($this->userService->getUsers($orgId));
    }

    public function edit($orgId, $id)
    {
        $result = $this->userService->getUserWithACL($id, $orgId);

        return $this->json($result);
    }
}