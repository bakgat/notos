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
     * @param $name
     * @return Group
     */
    public function groupOfName($name);
}