<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 13:51
 */

namespace Bakgat\Notos\Domain\Model\ACL;


interface RoleRepository
{
    /**
     * Get the Role by slug
     *
     * @param $slug
     * @return mixed
     */
    public function get($slug);
}