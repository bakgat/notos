<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 21/10/15
 * Time: 14:51
 */

namespace Bakgat\Notos\Domain\Model\Identity;


interface HashedPasswordSpecification
{
    /**
     * @param $value
     * @return bool
     */
    public function isSatisfiedBy($value);
}