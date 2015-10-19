<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 15:06
 */

namespace Bakgat\Notos\Exceptions;


class UnauthorizedException
{
    protected $status = '401';

    public function __construct() {
        $message = $this->build(func_get_args());

        parent::__construct($message);
    }
}