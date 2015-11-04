<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 2/11/15
 * Time: 21:12
 */

namespace Bakgat\Notos\Domain\Model\Curricula\Exceptions;

use Bakgat\Notos\Exceptions\NotFoundException;

class StructureNotFoundException extends NotFoundException
{
    public function __construct()
    {
        $args = func_get_args();
        array_unshift($args, 'curr_structure_not_found');

        call_user_func_array(array($this, 'parent::__construct'), $args);
    }
}