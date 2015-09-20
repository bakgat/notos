<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 11:02
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Location;


use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\Website;
use Bakgat\Notos\Domain\Model\Location\WebsitesRepository;
use Doctrine\ORM\EntityManager;

class WebsitesDoctrineORMRepository implements WebsitesRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $wsClass */
    private $wsClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->wsClass = 'Bakgat\Notos\Domain\Model\Location\Website';

    }

    /**
     * Returns all websites
     *
     * @return mixed
     */
    public function all()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('w')
            ->from($this->wsClass, 'w');
        return $qb->getQuery()->getResult();
    }

    /**
     * Get all websites, fully loaded with all relations
     *
     * @return mixed
     */
    public function full()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('w, wo, l, t')
            ->from($this->wsClass, 'w')
            ->leftJoin('w.objectives', 'wo')
            ->leftJoin('w.tags', 't')
            ->leftJoin('wo.levels', 'l')
            ->where(
                $qb->expr()->gt('l.level', '?1')
            )
            ->setParameter(1, 0);
        return $qb->getQuery()->getResult();
    }

    /**
     * Adds a new website
     *
     * @param Website $website
     * @return mixed
     */
    public function add(Website $website)
    {
        $this->em->persist($website);
        $this->em->flush();
    }

    /**
     * Updates an existing website
     *
     * @param Website $website
     * @return mixed
     */
    public function update(Website $website)
    {
        $this->em->persist($website);
        $this->em->flush();
    }

    /**
     * Find a website by it's id
     * @param $id
     * @return mixed
     */
    public function websiteofId($id)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('w, wo, l')
            ->from($this->wsClass, 'w')
            ->join('w.objectives', 'wo')
            ->leftJoin('wo.levels', 'l')
            ->where(
                $qb->expr()->eq('w.id', '?1')
            )
            ->setParameter(1, $id);
        return $qb->getQuery()->getSingleResult();
    }

    /**
     * Find a website by it's url
     *
     * @param URL $URL
     * @return mixed
     */
    public function websiteOfURL(URL $URL)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('w, wo, l, t')
            ->from($this->wsClass, 'w')
            ->join('w.objectives', 'wo')
            ->leftJoin('wo.levels', 'l')
            ->leftJoin('w.tags', 't')
            ->where(
                $qb->expr()->eq('w.url', '?1')
            )
            ->setParameter(1, $URL->toString());
        return $qb->getQuery()->getSingleResult();
    }


}