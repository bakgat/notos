<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 15:40
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Doctrine\Common\Collections\ArrayCollection;

interface OrganizationRepository {

    /**
     * Returns all organizations
     *
     * @return ArrayCollection
     */
    public function all();

    /**
     * Adds a new Organization
     *
     * @param Organization $org
     * @return void
     */
    public function add(Organization $org);

    /**
     * Updates an existing Organization
     *
     * @param Organization $org
     * @return void
     */
    public function update(Organization $org);

    /**
     * Find an organization by their id.
     *
     * @param $id
     * @return Organization
     * @throws OrganizationNotFoundException
     */
    public function organizationOfId($id);


    /**
     * Find an organization by their domain name
     *
     * @param string $domain_name
     * @return Organization
     */
    public function organizationOfDomain($domain_name);

}