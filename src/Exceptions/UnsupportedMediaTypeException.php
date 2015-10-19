<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 15:06
 */

namespace Bakgat\Notos\Exceptions;


class DuplicateException
{
    protected $status = '415';

    public function __construct() {
        $args = func_get_args();
        array_unshift($args, 'unsupported_media_type');

        $message = $this->build($args);

        parent::__construct($message);
    }
}