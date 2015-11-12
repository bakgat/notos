<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 14:17
 */

namespace Bakgat\Notos\Domain\Model\Identity;


class DomainNameIsUnique implements DomainNameSpecification
{
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->orgRepo = $organizationRepository;
    }

    /**
     * Check to see if the specification is satisfied
     *
     * @param DomainName $domainName
     * @return bool
     */
    public function isSatisfiedBy(DomainName $domainName)
    {
        if(!$this->orgRepo->organizationOfDomain($domainName)) {
            return true;
        }
        return false;
    }
}