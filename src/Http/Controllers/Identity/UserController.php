<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 15:31
 */

namespace Bakgat\Notos\Http\Controllers\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Services\Identity\UserService;
use Bakgat\Notos\Http\Controllers\Controller;
use Bakgat\Notos\Http\Requests\Identity\UserFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use JMS\Serializer\SerializerBuilder;

class UserController extends Controller
{

    /** @var UserService $userService */
    private $userService;

    public function __construct(UserService $userService)
    {

        parent::__construct();
        //$this->middleware('auth');
        $this->userService = $userService;
    }

    /**
     * Returns all basic information of users within an organization
     *
     * @param $orgId
     * @return mixed
     */
    public function index($orgId)
    {
        //$domain = str_replace('_', '.', $domain);
        return $this->json($this->userService->getUsers($orgId));
    }

    /**
     * Returns full profile of an user to update
     *
     * @param $orgId
     * @param $userId
     * @return mixed
     */
    public function edit($orgId, $userId)
    {

        $result = $this->userService->getUserWithACL($userId, $orgId);

        return $this->json($result);
    }

    /**
     * Returns a User with the complete profile loaded
     *
     * @param string|\Bakgat\Notos\Domain\Model\Identity\Username|null $username
     * @return \Bakgat\Notos\Domain\Model\Identity\User
     * @throws \Bakgat\Notos\Domain\Services\Identity\NoCurrentUserFoundException
     */
    public function profile($username, $domain)
    {
        $domain = str_replace('_', '.', $domain);
        $profile = $this->userService->getProfile(new Username($username), new DomainName($domain));

        return $this->json($profile);
    }

    /**
     * Returns the current user
     *
     * @return mixed
     */
    public function auth() {
        return $this->json($this->userService->getAuth());
    }

    /**
     * Updates an existing User
     *
     * @param UserFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function update($orgId, $userId, UserFormRequest $request)
    {
        $data = $request->all();
        $data = array_merge(['id' => $userId], $data);

        $user = $this->userService->update($data);
        return $this->json($user);
    }

    /**
     * Stores a new User in an organization
     *
     * @param null $orgId
     * @param UserFormRequest $request
     * @return \Symfony\Component\HttpFoundation\Response|static
     */
    public function store($orgId, UserFormRequest $request)
    {
        $data = $request->all();

        $user = $this->userService->add($data, $orgId);
        return $this->json($user);
    }

    /**
     * Deletes a user in an organization
     *
     * @param $userId
     */
    public function destroy($orgId, $userId)
    {
        $success = $this->userService->destroy($userId);
        if ($success) {
            response('', 204);
        } else {
            abort(404, 'User with id [' . $userId . '] failed.');
        }
    }

    /**
     * Resets the password of a given user
     *
     * @param $userId
     * @param Request $request
     * @return User
     */
    public function resetPassword($orgId, $userId, Request $request)
    {
        $password = $request->input('password');
        $user = $this->userService->userOfId($userId);
        if ($user) {
            $user = $this->userService->resetPassword($user, $password);
            return $this->json($user);
        } else {
            abort(404, 'User with id [' . $user . '] not found.');
        }
    }
}