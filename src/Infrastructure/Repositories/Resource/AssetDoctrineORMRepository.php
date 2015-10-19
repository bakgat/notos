<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 12:29
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Resource\Asset;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Doctrine\ORM\EntityManager;

class AssetDoctrineORMRepository implements AssetRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $assetClass */
    private $assetClass;

    public function __construct(EntityManager $em) {
        $this->em = $em;
        $this->assetClass = 'Bagkat\Notos\Domain\Model\Resource\Asset';
    }

    public function add(Asset $asset)
    {
        $this->em->persist($asset);
        $this->em->flush();
    }

    public function update(Asset $asset)
    {
        $this->em->persist($asset);
        $this->em->flush();
    }

    public function assetsOfType($mime_part)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->where(
                $qb->expr()->like('a.mime', '?1')
            )
            ->setParameter(1, '%' . $mime_part . '%');
        return $qb->getQuery()->getResult();
    }

    public function assetOfGuid($guid)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('a')
            ->from($this->assetClass, 'a')
            ->where(
                $qb->expr()->like('a.guid', '?1')
            )
            ->setParameter(1, $guid);

        return $qb->getQuery()->getOneOrNullResult();
    }
}