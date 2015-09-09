<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/07/15
 * Time: 22:01
 */

namespace Bakgat\Notos\Domain\Model\ACL;


trait HasPermission {
    use HasPermissionInheritance, Helper;

    /**
     * Return all permissions
     *
     * @return array
     */
    public function getPermissions()
    {
        //user permissions overriden from role
        $permissions = $this->getPermissionsInherited();

        // permissions based on role
        // more permissive permission wins
        // if user has multiple user_roles we keep
        // true values.
        foreach ($this->roles as $role) {
            foreach ($role->getPermissions() as $slug => $array) {
                if (array_key_exists($slug, $permissions)) {
                    foreach ($array as $clearance => $value) {
                        !$value ?: $permissions[$slug][$clearance] = true;
                    }
                } else {
                    $permissions = array_merge($permissions, [$slug => $array]);
                }
            }
        }
        return $permissions;
    }

    /**
     * Check if User has the given permission.
     *
     * @param  string $permission
     * @param  string $operator
     * @return bool
     */
    public function can($permission, $operator = null)
    {
        // user permissions including
        // all of user role permissions
        $merge = $this->getPermissions();
        // lets call our base can() method
        // from role class. $merge already
        // has user & role permissions
        return (new Role('foo'))->can($permission, $operator, $merge);
    }

    /**
     * Adds a permission to this role.
     *
     * @param Permission $permission
     */
    public function assignPermission(Permission $permission)
    {
        $this->permissions[] = $permission;
    }

    /**
     * Removes a permission from this role
     *
     * @param Permission $permission
     */
    public function revokePermission(Permission $permission)
    {
        $this->permissions->removeElement($permission);
    }

    public function syncPermissions(array $permissions) {
        //TODO:
    }

    public function revokeAllPermissions() {
        $this->permissions = [];
    }
}