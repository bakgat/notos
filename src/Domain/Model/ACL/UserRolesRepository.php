<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 11:20
 */

namespace Bakgat\Notos\Domain\Model\ACL;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;

interface UserRolesRepository
{
    /**
     * Gets all the roles associated with an user
     * @param User $user
     * @param Organization $organization
     * @return mixed
     */
    public function rolesOfUser(User $user, Organization $organization);

    /**
     * Adds an created UserRole
     *
     * @param UserRole $userRole
     * @return mixed
     */
    public function add(UserRole $userRole);

    /**
     * Register a new UserRole and saves it.
     *
     * @param User $user
     * @param Role $role
     * @param Organization $organization
     * @return mixed
     */
    public function register(User $user, Role $role , Organization $organization);
}