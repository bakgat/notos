<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 15:06
 */

namespace Bakgat\Notos\Exceptions;


class DuplicateException extends NotosException
{

    protected $status = '409';

    public function __construct() {
        $args = func_get_args();
        array_unshift($args, 'duplicate');

        $message = $this->build($args);

        parent::__construct($message);
    }
}