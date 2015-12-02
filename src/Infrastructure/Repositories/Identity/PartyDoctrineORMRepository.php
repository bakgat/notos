<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 1/12/15
 * Time: 14:42
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Identity;


use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\Model\Identity\PartyRepository;
use Bakgat\Notos\Domain\Model\Kind;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class PartyDoctrineORMRepository implements PartyRepository
{

    /** @var EntityManager $em */
    private $em;
    /** @var string $partyClass */
    private $partyClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->partyClass = 'Bakgat\Notos\Domain\Model\Identity\Party';
    }

    /**
     * @param $id
     * @return Party
     */
    public function partyOfId($id)
    {
        $party = $this->em->getRepository($this->partyClass)
            ->find($id);

        return $party;
    }

    /**
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function partiesOfKind(Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('p')
            ->from($this->partyClass, 'p')
            ->join('p.kind', 'k')
            ->where(
                $qb->expr()->eq('k.id', '?1')
            )
            ->setParameter(1, $kind->id());

        $parties = $query->getQuery()->getResult();
        return $parties;
    }

    /**
     * @param Name $name
     * @param Kind $kind
     * @return Party
     */
    public function partyOfNameAndKind(Name $name, Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('p')
            ->from($this->partyClass, 'p')
            ->join('p.kind', 'k')
            ->where(
                $qb->expr()->eq('k.id', '?1'),
                $qb->expr()->eq('p.lastName', '?2')
            )
            ->setParameter(1, $kind->id())
            ->setParameter(2, $name->toString());

        $party = $query->getQuery()->getOneOrNullResult();
        return $party;
    }

    /**
     * @param Name $name
     * @param Kind $kind
     * @return ArrayCollection
     */
    public function partiesLikeNameAndKind(Name $name, Kind $kind)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('p')
            ->from($this->partyClass, 'p')
            ->join('p.kind', 'k')
            ->where(
                $qb->expr()->eq('k.id', '?1'),
                $qb->expr()->like('p.lastName', '?2')
            )
            ->setParameter(1, $kind->id())
            ->setParameter(2, '%' . $name->toString() . '%');

        $parties = $query->getQuery()->getResult();
        return $parties;
    }

    /**
     * @param Party $party
     * @return void
     */
    public function add(Party $party)
    {
        $this->em->persist($party);
        $this->em->flush();
    }

    /**
     * @param Party $party
     * @return void
     */
    public function update(Party $party)
    {
        $this->em->persist($party);
        $this->em->flush();
    }
}