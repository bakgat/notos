<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 14:35
 */

namespace Bakgat\Notos\Domain\Model\Identity\Exceptions;


use Bakgat\Notos\Exceptions\NotFoundException;

class OrganizationNotFoundException extends NotFoundException
{

    public function __construct() {
        $args = func_get_args();
        array_unshift($args, 'org_not_found');

        call_user_func_array(array($this, 'parent::__construct'), $args);
    }
}