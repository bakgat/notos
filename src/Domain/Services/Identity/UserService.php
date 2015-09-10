<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 14:13
 */

namespace Bakgat\Notos\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\UserRepository;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserService
{
    /** @var Guard $guard */
    private $guard;
    /** @var UserRepository $userRepo */
    private $userRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(/*Guard $guard, */
        UserRepository $userRepository, OrganizationRepository $organizationRepository)
    {
        //$this->guard = $guard;

        $this->userRepo = $userRepository;
        $this->orgRepo = $organizationRepository;
    }

    public function login($credentials, $remember)
    {
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            $loggedInto = $this->organizationsOfUser($user)[0];

            Auth::setUser($this->getUserWithACL($user->id(), $loggedInto->id()));

            Session::put('loggedInto', $loggedInto);

            return true;
        }
        return false;
    }

    public function getUsers($orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        return $this->userRepo->all($organization);
    }

    /**
     * @param string|null $username
     * @param string|null $domainname
     * @return mixed|null
     * @throws NoCurrentOrganizationLoggedInto
     * @throws NoCurrentUserFoundException
     */
    public function getUserWithACL($id = null, $orgId = null)
    {
        $user = null;
        $organization = null;

        if (!$id) {

            $user = $this->guard->user();
        } else {
            $user = $this->userRepo->userOfId($id);
        }

        if (!$user) {
            throw new NoCurrentUserFoundException;
        }

        if (!$orgId) {

            $organization = $this->guard->user()->loggedInto();
            if (!$organization) {
                throw new NoCurrentOrganizationLoggedInto;
            }
        }

        if (!$organization) {
            $organization = $this->orgRepo->organizationOfId($orgId);
        }

        $user = $this->userRepo->userWithACL($user, $organization);


        return $user;
    }

    public function organizationsOfUser(User $user)
    {
        $orgs = $this->userRepo->organizationsOfUser($user);
        return $orgs;
    }
}