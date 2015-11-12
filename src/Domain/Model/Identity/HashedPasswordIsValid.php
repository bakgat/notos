<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 21/10/15
 * Time: 14:52
 */

namespace Bakgat\Notos\Domain\Model\Identity;


class HashedPasswordIsValid implements HashedPasswordSpecification
{

    /**
     * @param $avlue
     * @return bool
     */
    public function isSatisfiedBy($value)
    {
        if($value === '' || !str_contains($value, '$')) {
            return false;
        }

        $parts = explode('$', $value);

        if(count($parts) < 4) {
            return false;
        }

        $type = $parts[1];
        $strength = $parts[2];
        $saltHash = $parts[3];

        return $type==='2y' && //only allow salted
            preg_match('/^[a-zA-Z0-9\.\/]{53}$/', $saltHash);
    }
}