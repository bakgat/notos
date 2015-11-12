<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 21/10/15
 * Time: 15:08
 */

namespace Bakgat\Notos\Domain\Model\ACL\Exceptions;


use Bakgat\Notos\Exceptions\NotFoundException;

class RoleNotFoundException extends NotFoundException
{
    public function __construct() {
        $args = func_get_args();
        array_unshift($args, 'role_not_found');

        call_user_func_array(array($this, 'parent::__construct'), $args);
    }
}