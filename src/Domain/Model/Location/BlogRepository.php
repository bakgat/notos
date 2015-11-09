<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 25/09/15
 * Time: 11:39
 */

namespace Bakgat\Notos\Domain\Model\Location;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Doctrine\Common\Collections\ArrayCollection;

interface BlogRepository
{
    /**
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization);

    public function add(Blog $blog);

    public function update(Blog $blog);

    public function remove(Blog $blog);

    /**
     * @param $id
     * @return Blog
     */
    public function blogOfId($id);

}