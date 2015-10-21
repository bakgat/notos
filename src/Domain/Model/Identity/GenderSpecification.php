<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 21/10/15
 * Time: 14:51
 */

namespace Bakgat\Notos\Domain\Model\Identity;


interface GenderSpecification
{
    /**
     * @param $gender
     * @return bool
     */
    public function isSatisfiedBy($gender);
}