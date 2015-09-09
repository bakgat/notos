<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 14:13
 */

namespace Bakgat\Notos\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Identity\UserRepository;
use Illuminate\Contracts\Auth\Guard;

class UserService
{
    /** @var Guard $guard */
    private $guard;
    /** @var UserRepository $userRepo */
    private $userRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(/*Guard $guard, */UserRepository $userRepository, OrganizationRepository $organizationRepository)
    {
        //$this->guard = $guard;

        $this->userRepo = $userRepository;
        $this->orgRepo = $organizationRepository;
    }

    /**
     * @param string|null $username
     * @param string|null $domainname
     * @return mixed|null
     * @throws NoCurrentOrganizationLoggedInto
     * @throws NoCurrentUserFoundException
     */
    public function getUserWithACL($username = null, $domainname = null)
    {
        $user = null;
        $organization = null;

        if (!$username) {
            /*if (!$this->guard->user()) {
                throw new NoCurrentUserFoundException;
            }*/
            $username = $this->guard->user()->username();
        }

        if (!$domainname) {
            if (!$this->guard->user()) {
                throw new NoCurrentUserFoundException;
            }
            $organization = $this->guard->user()->loggedInto();
            if (!$organization) {
                throw new NoCurrentOrganizationLoggedInto;
            }
        }

        if (!$organization) {
            $organization = $this->orgRepo->organizationOfDomain($domainname);
        }

        $user = $this->userRepo->userOfUsernameWithACL($username, $organization);


        return $user;
    }
}