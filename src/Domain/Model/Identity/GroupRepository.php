<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/09/15
 * Time: 23:03
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Kind;

interface GroupRepository
{
    /**
     * Get all groups of a kind
     *
     * @param Kind $kind
     * @return mixed
     */
    public function groupsOfKind(Kind $kind);

    /**
     * Find a group by it's name
     * @param $name
     * @return Group
     */
    public function groupOfName($name);

    /**
     * Find a group by it's id
     * @param $id
     * @return Group
     */
    public function groupOfId($id);
}