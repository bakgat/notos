<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 19:09
 */

namespace Bakgat\Notos\Domain\Model\Relations;


use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\Model\Kind;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

interface PartyRelationRepository {

    /**
     * Adds a new party 2 party relation
     * @param PartyRelation $relation
     * @return void
     */
    public function add(PartyRelation $relation);

    /**
     * Destroys a relation that is alive at this moment. (setting end to current timestamp)
     *
     * @param Party $context
     * @param Party $reference
     * @param Kind $kind
     */
    public function destroy(Party $context, Party $reference, Kind $kind);


    //TODO: destroy methods before Start, end, between, end included, ...
    /**
     * Destroys a relation that was alive before a given date
     *
     * @param Party $context
     * @param Party $reference
     * @param DateTime $date
     * @param Kind $kind
     */
    public function destroyBefore(Party $context, Party $reference, DateTime $date, Kind $kind);

    /**
     * Returns a collection of references for a given party.
     *
     * @param Party $context
     * @return ArrayCollection
     */
    public function referencesOfContext(Party $context);

    /**
     * Returns a collection of references for a given party that has a specified kind.
     *
     * @param Party $context
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function referencesOfContextByKind(Party $context, Kind $kind);

    /**
     * Returns a collection of destroyed references for a given party.
     *
     * @param Party $context
     * @return ArrayCollection
     */
    public function destroyedReferencesOfContext(Party $context);

    /**
     * Returns a collection of destroyed references for a given party that has a specified kind.
     *
     * @param Party $context
     * @param Kind $kind
     *
     * @return ArrayCollection
     */
    public function destroyedReferencesOfContextByKind(Party $context, Kind $kind);

    /**
     * Returns a collection of parties where the given party is the reference.
     *
     * @param Party $reference
     * @return ArrayCollection
     */
    public function contextOfReference(Party $reference);

    /**
     * Returns a collection of parties where the given party is the reference, and the relation has a specified kind.
     *
     * @param Party $reference
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function contextOfReferenceByKind(Party $reference, Kind $kind);

    /**
     * Returns a collection of parties where the given party once was the reference.
     *
     * @param Party $reference
     * @return ArrayCollection
     */
    public function destroyedContextOfReference(Party $reference);

    /**
     * Returns a collection of parties where the given party once was the reference, and the relation has a specified kind.
     *
     * @param Party $reference
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function destroyedContextOfReferenceByKind(Party $reference, Kind $kind);
}