<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/06/15
 * Time: 23:01
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Doctrine\Common\Collections\ArrayCollection;

interface UserRepository {

    /**
     * Returns all users in a organization
     *
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization);
    /**
     * Adds a new User
     *
     * @param User $user
     * @return void
     */
    public function add(User $user);

    /**
     * Updates an existing User
     *
     * @param User $user
     * @return void
     */
    public function update(User $user);

    /**
     * Find a user by their id
     *
     * @param $id
     * @return User
     */
    public function userOfId($id);

    /**
     * Find a user by their email address
     *
     * @param $email
     * @return User
     */
    public function userOfEmail(Email $email);

    /**
     * Find a user by their username
     *
     * @param Username $username
     * @return User
     */
    public function userOfUsername(Username $username);

    /**
     * Find a user by their username and load all ACL
     *
     * @param Username $user
     * @param Organization $organization
     * @return User
     */
    public function userWithACL(Username $user, Organization $organization);

    /**
     * Finds the organizations in which the given user is registered.
     *
     * @param User $user
     * @return ArrayCollection
     */
    public function organizationsOfUser(User $user);
}