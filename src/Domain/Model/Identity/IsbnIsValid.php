<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 29/06/15
 * Time: 12:06
 */

namespace Bakgat\Notos\Domain\Model\Identity;


class IsbnIsValid implements IsbnSpecification {

    /**
     * Check to see if the specification is satisfied
     *
     * @param Isbn $isbn
     * @return bool
     */
    public function isSatisfiedBy(Isbn $isbn)
    {
        return $isbn->isValid();
    }
}