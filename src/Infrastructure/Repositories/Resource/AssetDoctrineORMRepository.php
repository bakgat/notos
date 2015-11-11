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

class AssetDoctrineORMRepository implements AssetRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $assetClass */
    private $assetClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->assetClass = 'Bakgat\Notos\Domain\Model\Resource\Asset';
    }

    /**
     * @param Organization $klimtoren
     * @return ArrayCollection
     */
    public function all(Organization $klimtoren)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->join('a.organization', 'o')
            ->where(
                $qb->expr()->eq('o.id', ':org')
            )
            ->setParameter('org', $klimtoren->id());
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
     * @param Organization $organization
     * @param $mime_part
     * @return ArrayCollection
     */
    public function assetsOfType(Organization $organization, $mime_part)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->join('a.organization', 'o')
            ->where(
                $qb->expr()->like('a.mime', ':mimepart'),
                $qb->expr()->eq('o.id', ':org')
            )
            ->setParameter('mimepart', '%' . $mime_part . '%')
            ->setParameter('org', $organization->id());
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


}