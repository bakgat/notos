<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 19:20
 */

namespace Bakgat\Notos\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\Model\Kind;
use Bakgat\Notos\Domain\Model\Relations\PartyRelation;
use Bakgat\Notos\Domain\Model\Relations\PartyRelationRepository;
use Bakgat\Notos\Support\NotosDB;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class PartyRelationDoctrineORMRepository implements PartyRelationRepository
{

    /** @var  EntityManager */
    private $em;
    /** @var string */
    private $class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Relations\PartyRelation';
    }

    /**
     * Adds a new party 2 party relation
     * @param PartyRelation $relation
     * @return void
     */
    public function add(PartyRelation $relation)
    {
        $this->em->merge($relation);
        $this->em->flush();
    }

    /**
     * Destroys a relation that is alive at this moment. (setting end to current timestamp)
     *
     * @param Party $context
     * @param Party $reference
     * @param Kind $kind
     */
    public function destroy(Party $context, Party $reference, Kind $kind)
    {
        $relation = $this->em->getRepository($this->class)
            ->findOneBy(['context' => $context->id(),
                'reference' => $reference->id(),
                'kind' => $kind,
                'end' => null]);
        if ($relation) {
            $relation->destroy();
            $this->em->persist($relation);
            $this->em->flush();
        }
    }

    /**
     * Destroys a relation that was alive before a given date
     *
     * @param Party $context
     * @param Party $reference
     * @param DateTime $date
     * @param Kind $kind
     */
    public function destroyBefore(Party $context, Party $reference, DateTime $date, Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->update($this->class, 'pr')
            ->set('pr.end', '?5')
            ->where(
                $qb->expr()->eq('pr.context', '?1'),
                $qb->expr()->eq('pr.reference', '?2'),
                $qb->expr()->lt('pr.start', '?3'),
                $qb->expr()->isNull('pr.end'),
                $qb->expr()->eq('pr.kind', '?4')
            )
            ->setParameter(1, $context->id())
            ->setParameter(2, $reference->id())
            ->setParameter(3, $date)
            ->setParameter(4, $kind)
            ->setParameter(5, new DateTime);
        $qb->getQuery()->execute();
    }

    /**
     * Returns a collection of references for a given party.
     *
     * @param Party $context
     * @return ArrayCollection
     */
    public function referencesOfContext(Party $context)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.context', '?1')
            );
        NotosDB::ObjAlive($qb, '?2')
            ->setParameter(1, $context)
            ->setParameter(2, new DateTime);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Returns a collection of references for a given party that has a specified kind.
     *
     * @param Party $context
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function referencesOfContextByKind(Party $context, Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.context', '?1'),
                $qb->expr()->eq('pr.kind', '?2')
            );

        NotosDB::ObjAlive($qb, '?3')
            ->setParameter(1, $context)
            ->setParameter(2, $kind)
            ->setParameter(3, new DateTime);


        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Returns a collection of destroyed references for a given party.
     *
     * @param Party $context
     * @return ArrayCollection
     */
    public function destroyedReferencesOfContext(Party $context)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.context', '?1')
            );
        NotosDB::ObjDestroyed($qb, '?2')
            ->setParameter(1, $context)
            ->setParameter(2, new DateTime);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Returns a collection of destroyed references for a given party that has a specified kind.
     *
     * @param Party $context
     * @param Kind $kind
     *
     * @return ArrayCollection
     */
    public function destroyedReferencesOfContextByKind(Party $context, Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.context', '?1'),
                $qb->expr()->eq('pr.kind', '?2')
            );
        NotosDB::ObjDestroyed($qb, '?3')
            ->setParameter(1, $context)
            ->setParameter(2, $kind)
            ->setParameter(3, new DateTime);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Returns a collection of parties where the given party is the reference.
     *
     * @param Party $reference
     * @return ArrayCollection
     */
    public function contextOfReference(Party $reference)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.reference', '?1')
            );
        NotosDB::ObjAlive($qb, '?2')
            ->setParameter(1, $reference)
            ->setParameter(2, new DateTime);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Returns a collection of parties where the given party is the reference, and the relation has a specified kind.
     *
     * @param Party $reference
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function contextOfReferenceByKind(Party $reference, Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.reference', '?1'),
                $qb->expr()->eq('pr.kind', '?2')
            );
        NotosDB::ObjAlive($qb, '?3')
            ->setParameter(1, $reference)
            ->setParameter(2, $kind)
            ->setParameter(3, new DateTime);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Returns a collection of parties where the given party once was the reference.
     *
     * @param Party $reference
     * @return ArrayCollection
     */
    public function destroyedContextOfReference(Party $reference)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.reference', '?1')
            );
        NotosDB::ObjDestroyed($qb, '?2')
            ->setParameter(1, $reference)
            ->setParameter(2, new DateTime);

        $query = $qb->getQuery();
        return $query->getResult();
    }

    /**
     * Returns a collection of parties where the given party once was the reference, and the relation has a specified kind.
     *
     * @param Party $reference
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function destroyedContextOfReferenceByKind(Party $reference, Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pr')
            ->from($this->class, 'pr')
            ->where(
                $qb->expr()->eq('pr.reference', '?1'),
                $qb->expr()->eq('pr.kind', '?2')
            );
        NotosDB::ObjDestroyed($qb, '?3')
            ->setParameter(1, $reference)
            ->setParameter(2, $kind)
            ->setParameter(3, new DateTime);

        $query = $qb->getQuery();
        return $query->getResult();
    }
}