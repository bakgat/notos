<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 14:16
 */

namespace Bakgat\Notos\Domain\Model\Identity;

interface DomainNameSpecification
{
    /**
     * Check to see if the specification is satisfied
     *
     * @param DomainName $domainName
     * @return bool
     */
    public function isSatisfiedBy(DomainName $domainName);
}