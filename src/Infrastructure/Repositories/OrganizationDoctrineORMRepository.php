<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 15:45
 */

namespace Bakgat\Notos\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\Domain;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Exceptions\DuplicateException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Session;

class OrganizationDoctrineORMRepository implements OrganizationRepository
{

    /** @var  EntityManagerInterface */
    protected $em;
    /** @var  string */
    protected $class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Identity\Organization';
    }

    /**
     * Returns all organizations
     *
     * @return ArrayCollection
     */
    public function all()
    {
        return $this->em->getRepository($this->class)
            ->findAll();
    }

    /**
     * Adds a new Organization
     *
     * @param Organization $org
     * @return void
     */
    public function add(Organization $org)
    {
        $this->em->persist($org);
        $this->em->flush();
    }

    /**
     * Updates an existing Organization
     *
     * @param Organization $org
     * @return void
     */
    public function update(Organization $org)
    {
        $this->em->persist($org);
        $this->em->flush();
    }

    /**
     * Find an Organization by their id.
     *
     * @param $id
     * @return Organization
     * @throws DuplicateException
     */
    public function organizationOfId($id)
    {
        $org = $this->em->getRepository($this->class)
            ->findOneBy(['id' => $id]);

        if(!$org) {
            throw new DuplicateException('URL', 'klimtoren.be');
        }

        return $org;
    }


    /**
     * Find an Organization by their domain name
     *
     * @param string $domain_name
     * @return Organization
     */
    public function organizationOfDomain($domain_name)
    {
        return $this->em->getRepository($this->class)
            ->findOneBy(['domain_name' => $domain_name]);
    }
}