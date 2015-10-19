<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 15:30
 */

namespace Bakgat\Notos\Domain\Model\Descriptive\Exceptions;


use Bakgat\Notos\Exceptions\PreconditionFailedException;

class TagNameNotValidException extends PreconditionFailedException
{
    public function __construct() {
        $args = func_get_args();
        array_unshift($args, 'tagname_not_valid');

        call_user_func_array(array($this, 'parent::__construct'), $args);
    }
}