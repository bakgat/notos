<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 1/12/15
 * Time: 14:19
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Kind;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A generic repository for querying parties in the datastore
 * Interface PartyRepository
 * @package Bakgat\Notos\Domain\Model\Identity
 */
interface PartyRepository
{
    /**
     * @param $id
     * @return Party
     */
    public function partyOfId($id);

    /**
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function partiesOfKind(Kind $kind);

    /**
     * @param Name $name
     * @param Kind $kind
     * @return Party
     */
    public function partyOfNameAndKind(Name $name, Kind $kind);

    /**
     * @param Name $name
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function partiesLikeNameAndKind(Name $name, Kind $kind);

    /**
     * @param Party $party
     * @return void
     */
    public function add(Party $party);

    /**
     * @param Party $party
     * @return void
     */
    public function update(Party $party);
}