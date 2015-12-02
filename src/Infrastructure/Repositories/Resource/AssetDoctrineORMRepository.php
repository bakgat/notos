<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 12:29
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Resource\Asset;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;

class AssetDoctrineORMRepository implements AssetRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $assetClass */
    private $assetClass;
    /** @var  string $resourceClass */
    private $resourceClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->resourceClass = 'Bakgat\Notos\Domain\Model\Resource\Resource';
        $this->assetClass = 'Bakgat\Notos\Domain\Model\Resource\Asset';
    }

    /**
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->join('a.organization', 'o')
            ->where(
                $qb->expr()->eq('o.id', ':org')
            )
            ->setParameter('org', $organization->id());
        return $qb->getQuery()->getResult();
    }

    /**
     * @param Asset $asset
     * @return mixed|void
     */
    public function add(Asset $asset)
    {
        $this->em->persist($asset);
        $this->em->flush();
    }

    /**
     * @param Asset $asset
     * @return mixed|void
     */
    public function update(Asset $asset)
    {
        $this->em->persist($asset);
        $this->em->flush();
    }

    /**
     * @param $organization
     * @param $mime_part
     * @return ArrayCollection
     * @internal param $type
     */
    public function assetsOfMime($organization, $mime_part)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->where($qb->expr()->like('a.mime', ':mimepart'))
            ->setParameter('mimepart', '%' . $mime_part . '%');


        if ($organization && $organization instanceof Organization) {
            $qb->join('a.organization', 'o')
                ->andWhere($qb->expr()->eq('o.id', ':org'))
                ->setParameter('org', $organization->id());
        } else if (!$organization) {
            $qb->andWhere($qb->expr()->isNull('a.organization'));
        }

        $qb->orderBy('a.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }

    /**
     * @param $guid
     * @return Asset
     */
    public function assetOfGuid($guid)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->where(
                $qb->expr()->like('a.guid', ':guid')
            )
            ->setParameter('guid', $guid);

        return $qb->getQuery()->getOneOrNullResult();
    }


    /**
     * @param $organization
     * @param $mime_part
     * @param $type
     * @return ArrayCollection
     */
    public function assetsOfMimeAndType($organization, $mime_part, $type)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->where(
                $qb->expr()->like('a.mime', ':mimepart'),
                $qb->expr()->like('a.type', ':type')
            )
            ->setParameter('mimepart', '%' . $mime_part . '%')
            ->setParameter('type', strtolower($type));


        if ($organization && $organization instanceof Organization) {
            $qb->join('a.organization', 'o')
                ->andWhere($qb->expr()->eq('o.id', ':org'))
                ->setParameter('org', $organization->id());
        } else if (!$organization) {
            $qb->andWhere($qb->expr()->isNull('a.organization'));
        }

        $qb->orderBy('a.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}