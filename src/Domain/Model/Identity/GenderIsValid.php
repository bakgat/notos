<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 21/10/15
 * Time: 14:52
 */

namespace Bakgat\Notos\Domain\Model\Identity;


class GenderIsValid implements GenderSpecification
{
    public static $ALLOWED = [
        'm',
        'f',
        'male',
        'female',
        'M',
        'F',
        'O',
        'Male',
        'Female',
        'Other'
    ];

    /**
     * @param $gender
     * @return bool
     */
    public function isSatisfiedBy($gender)
    {

        if (in_array($gender, GenderIsValid::$ALLOWED)) {
            return true;
        }
        return false;
    }
}