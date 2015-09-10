<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 15:31
 */

namespace Bakgat\Notos\Http\Controllers;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Services\Identity\UserService;

class UserController extends Controller
{

    /** @var UserService $userService */
    private $userService;

    public function __construct(UserService $userService)
    {
        //$this->middleware('auth');
        $this->userService = $userService;
    }

    public function index()
    {

    }

    public function edit($id)
    {
        return $this->userService->getUserWithACL(new Username('karl.vaniseghem@klimtoren.be'), new Domainname('klimtoren.be'));
    }
}