<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 14:13
 */

namespace Bakgat\Notos\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\ACL\RoleRepository;
use Bakgat\Notos\Domain\Model\ACL\UserRolesRepository;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\UsernameIsNotUniqueException;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\UserNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Identity\UsernameIsUnique;
use Bakgat\Notos\Domain\Model\Identity\UsernameSpecification;
use Bakgat\Notos\Domain\Model\Identity\UserRepository;
use Bakgat\Notos\Exceptions\DuplicateException;
use DateTime;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserService
{
    /** @var Guard $guard */
    private $guard;
    /** @var UserRepository $userRepo */
    private $userRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;
    /** @var RoleRepository $roleRepo */
    private $roleRepo;
    /** @var UserRolesRepository $userRoleRepo */
    private $userRoleRepo;
    /** @var Hasher $hasher */
    private $hasher;
    /** @var UsernameSpecification $usernameSpec */
    private $usernameSpec;

    public function __construct(/*Guard $guard, */
        UserRepository $userRepository, OrganizationRepository $organizationRepository,
        RoleRepository $roleRepository, UserRolesRepository $userRolesRepository,
        Hasher $hasher)
    {
        //$this->guard = $guard;

        $this->userRepo = $userRepository;
        $this->orgRepo = $organizationRepository;
        $this->roleRepo = $roleRepository;
        $this->userRoleRepo = $userRolesRepository;
        $this->hasher = $hasher;
    }

    public function login($credentials, $remember)
    {
        Session::forget('profile');
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            $realm = $this->organizationsOfUser($user)[0];

            //Auth::user should contain all information about a user
            //so refill it
            $auth_user = $this->getProfile($user->id(), $realm->id());
            $profile_vars = [
                'userId' => $user->id(),
                'realmId' => $realm->id()
            ];
            Session::put('profile_vars', $profile_vars);
            Auth::setUser($auth_user);

            return true;
        }
        return false;
    }

    /**
     * @param $orgId
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @throws OrganizationNotFoundException
     */
    public function getUsers($orgId)
    {
        $organization = $this->organizationOfId($orgId);
        return $this->userRepo->all($organization);
    }

    public function getAuth()
    {
        $profile_vars = Session::get('profile_vars');

        $user = $this->getProfile($profile_vars['userId'], $profile_vars['realmId']);
        return $user;
    }

    /**
     * @param $id
     * @return User
     * @throws UserNotFoundException
     */
    public function userOfId($id)
    {
        $user = $this->userRepo->userOfId($id);
        if (!$user) {
            throw new UserNotFoundException($id);
        }
        return $user;
    }

    /**
     * @param $username
     * @return User
     * @throws UserNotFoundException
     */
    public function userOfUsername($username)
    {
        $user = $this->userRepo->userOfUsername(new Username($username));
        if (!$user) {
            throw new UserNotFoundException($username);
        }
        return $user;
    }

    /**
     * @param $userId
     * @param $orgId
     * @return mixed|null
     * @throws OrganizationNotFoundException
     * @throws UserNotFoundException
     */
    public function getUserWithACL($userId, $orgId)
    {
        $organization = $this->organizationOfId($orgId);
        $user = $this->userOfId($userId);

        $user = $this->userRepo->userWithACL($user->username(), $organization);
        return $user;
    }

    /**
     * @param $userId
     * @param $orgId
     * @return User
     */
    public function getProfile($userId, $orgId)
    {
        $organization = $this->organizationOfId($orgId);
        $user = $this->userOfId($userId);

        //fill user with ALL ACL info
        $user = $this->userRepo->userWithACL($user->username(), $organization);

        //add rest of available organizations
        $this->organizationsOfUser($user);

        //the given domainname is the realm
        $user->setRealm($organization);

        return $user;
    }

    /**
     * Adds a new user to an organization.
     *
     * @param $data
     * @param $orgId
     * @return User
     * @throws DuplicateException
     * @throws OrganizationNotFoundException
     */
    public function add($data, $orgId)
    {
        //extract all data from array
        $firstName = new Name($data['first_name']);
        $lastName = new Name($data['last_name']);
        $userName = new Username($data['username']);
        $hashedPwd = new HashedPassword(bcrypt($data['password']));
        $gender = new Gender($data['gender']);

        $email = null;
        if (isset($data['reset_email'])) {
            $email = new Email($data['reset_email']);
        } else {
            $email = new Email($data['username']);
        }

        //find the organization
        $organization = $this->organizationOfId($orgId);

        //only unique usernames
        $this->checkUsernameIsUnique($userName);

        //all valid, now GO!
        $user = User::register($firstName, $lastName, $userName, $hashedPwd, $email, $gender);
        $this->userRepo->add($user);

        //make this user a 'user' of the organization
        $this->addUserToRole($user, 'user', $organization);

        //return the entire filled profile (ACL included)
        //TODO speed by manually setting $user->setUserRoles  or $user->addUserRole ... to fill getRoles()
        return $this->getProfile($user->id(), $orgId);
    }

    /**
     * Updates an existing user.
     *
     * @param $data
     * @return User
     * @throws UserNotFoundException
     */
    public function update($data)
    {
        $user = $this->userOfId($data['id']);

        $user->setFirstName(new Name($data['first_name']));
        $user->setLastName(new Name($data['last_name']));
        $user->setGender(new Gender($data['gender']));

        //do not update username here. Therefore there must be another Service method
        $this->userRepo->update($user);

        return $user;
    }

    /**
     * Soft deletes a user with a given id
     *
     * @param $userId
     * @return bool
     * @throws UserNotFoundException
     */
    public function destroy($userId)
    {
        //TODO: delete in org relationship too

        $user = $this->userOfId($userId);

        $user->setDeletedAt(new DateTime);
        $this->userRepo->update($user);

        return true;
    }

    /**
     * Resets a given users password
     *
     * @param User $user
     * @param $pwd
     * @return User
     */
    public function resetPassword(User $user, $pwd)
    {
        $hashed = $this->hasher->make($pwd);
        $user->setPassword(new HashedPassword($hashed));
        $this->userRepo->update($user);

        return $user;
    }

    /**
     * @param User $user
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function organizationsOfUser(User $user)
    {
        $orgs = $this->userRepo->organizationsOfUser($user);
        $user->setOrganizations($orgs);
        return $orgs;
    }

    /**
     * @param $user
     * @param $rolename
     * @param $organization
     */
    public function addUserToRole($user, $rolename, $organization)
    {
        $role = $this->roleRepo->get($rolename);

        $this->userRoleRepo->register($user, $role, $organization);
    }

    /* ***************************************************
     * PRIVATE VALIDATION METHODS
     * **************************************************/

    /**
     * @param Username $username
     * @throws DuplicateException
     */
    private function checkUsernameIsUnique(Username $username)
    {
        $specification = new UsernameIsUnique($this->userRepo);
        if (!$specification->isSatisfiedBy($username)) {
            throw new DuplicateException('gebruikersnaam', $username->toString());
        }
    }

    /**
     * @param $orgId
     * @return \Bakgat\Notos\Domain\Model\Identity\Organization
     * @throws OrganizationNotFoundException
     */
    private function organizationOfId($orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }
        return $organization;
    }

}