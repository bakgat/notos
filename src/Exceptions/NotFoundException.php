<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 14:32
 */

namespace Bakgat\Notos\Exceptions;


class NotFoundException extends NotosException
{

    protected $status = '404';

    public function __construct() {
        $message = $this->build(func_get_args());

        parent::__construct($message);
    }
}