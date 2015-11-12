<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 14:35
 */

namespace Bakgat\Notos\Domain\Model\Location\Exceptions;


use Bakgat\Notos\Exceptions\NotFoundException;

class WebsiteNotFoundException extends NotFoundException
{

    public function __construct() {
        $args = func_get_args();
        array_unshift($args, 'website_not_found');

        call_user_func_array(array($this, 'parent::__construct'), $args);
    }
}