<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 10/09/15
 * Time: 15:29
 */

namespace Bakgat\Notos\Domain\Model\ACL;

use JMS\Serializer\Annotation as JMS;

trait HasRole
{
    use Helper;

    /**
     * Returns an array of slugs of all user_roles.
     *
     * @return array
     * @JMS\VirtualProperty
     */
    public function getRoles()
    {
        $result = [];
        foreach ($this->userRoles() as $userRole) {
            $result[] = $userRole->role()->slug();
        }
        return $result;
    }

    /**
     * Scope to select users having a specific
     * role. Role can be an id or slug.
     *
     * @param QueryBuilder $qb
     * @param int|string $role
     * @return QueryBuilder
     */
    public function scopeRole($qb, $role)
    {
        //TODO:Scope to select users having a specific
        //role. Role can be an id or slug.
    }

    /**
     *  Checks if the user has the given role(s).
     *
     *
     * @param $slug
     * @param null $operator
     * @return bool
     */
    public function is($slug, $operator = null)
    {
        $operator = is_null($operator) ? $this->parseOperator($slug) : $operator;

        $roles = $this->getRoles();
        $slug = $this->hasDelimiterToArray($slug);

        if (is_array($slug)) {
            if (!in_array($operator, ['and', 'or'])) {
                $e = 'Invalid operator, available operators are "and", "or".';
                throw new \InvalidArgumentException($e);
            }
            $call = 'isWith' . ucwords($operator);
            return $this->$call($slug, $roles);
        }
        return in_array($slug, $roles);
    }

    /**
     * Adds a role
     *
     * @param Role $role
     */
    public function assignRole(Role $role)
    {
        $this->roles[] = $role;
    }

    /**
     * Removes a role
     *
     * @param Role $role
     */
    public function revokeRole(Role $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Syncs the given role(s) with the user.
     *
     * @param array $role
     */
    public function syncRoles(array $role)
    {
        //TODO
    }

    /**
     * Revokes all user_roles from the user.
     */
    public function revokeAllRoles()
    {
        $this->roles = [];
    }

    /* ***************************************************
     * PROTECTED FUNCTIONS
     * **************************************************/
    /**
     * @param $slug
     * @param $roles
     * @return bool
     */
    protected function isWithAnd($slug, $roles)
    {
        foreach ($slug as $check) {
            if (!in_array($check, $roles)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $slug
     * @param $roles
     * @return bool
     */
    protected function isWithOr($slug, $roles)
    {
        foreach ($slug as $check) {
            if (in_array($check, $roles)) {
                return true;
            }
        }
        return false;
    }

}