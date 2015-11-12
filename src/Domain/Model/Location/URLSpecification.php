<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 11:45
 */

namespace Bakgat\Notos\Domain\Model\Location;


interface URLSpecification
{
    /**
     * Check to see if the specification is satisfied
     *
     * @param URL $url
     * @return bool
     */
    public function isSatisfiedBy(URL $url);
}