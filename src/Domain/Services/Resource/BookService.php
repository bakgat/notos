<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 15:14
 */

namespace Bakgat\Notos\Domain\Services\Resource;


use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Resource\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;

class BookService
{
    /** @var BookRepository $bookRepo */
    private $bookRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(BookRepository $bookRepository, OrganizationRepository $organizationRepository) {
        $this->bookRepo = $bookRepository;
        $this->orgRepo = $organizationRepository;
    }

    /**
     * @param $orgId
     * @return ArrayCollection
     * @throws OrganizationNotFoundException
     */
    public function all($orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if(!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }

        return $this->bookRepo->all($organization);
    }
}