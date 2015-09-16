<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 27/06/15
 * Time: 21:17
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;


interface IsbnSpecification {
    /**
     * Check to see if the specification is satisfied
     *
     * @param Isbn $isbn
     * @return bool
     */
    public function isSatisfiedBy(Isbn $isbn);
}