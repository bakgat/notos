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
}