<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 14:18
 */

namespace Bakgat\Notos\Domain\Model\Identity\Exceptions;


use Bakgat\Notos\Exceptions\PreconditionFailedException;

class DomainNameNotValidException extends PreconditionFailedException
{
    public function __construct() {
        $args = func_get_args();
        array_unshift($args, 'domainname_not_valid');

        call_user_func_array(array($this, 'parent::__construct'), $args);
    }
}