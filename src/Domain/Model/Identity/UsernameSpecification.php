<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 13:55
 */

namespace Bakgat\Notos\Domain\Model\Identity;


interface UsernameSpecification
{
    /**
     * Check to see if the specification is satisfied
     *
     * @param Username $username
     * @return bool
     */
    public function isSatisfiedBy(Username $username);
}