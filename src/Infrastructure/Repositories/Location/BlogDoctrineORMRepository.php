<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 25/09/15
 * Time: 12:22
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Location;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Location\Blog;
use Bakgat\Notos\Domain\Model\Location\BlogRepository;
use Doctrine\ORM\EntityManager;

class BlogDoctrineORMRepository implements BlogRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $blogClass */
    private $blogClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->blogClass = 'Bakgat\Notos\Domain\Model\Location\Blog';
    }

    public function all(Organization $organization)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('b, i')
            ->from($this->blogClass, 'b')
            ->leftJoin('b.image', 'i')
            ->where(
                $qb->expr()->eq('b.organization', '?1')
            )
            ->orderBy('b.weborder')
            ->setParameter(1, $organization->id());
        return $qb->getQuery()->getResult();
    }

    public function add(Blog $blog)
    {
        $this->em->persist($blog);
        $this->em->flush();
    }

    public function update(Blog $blog)
    {
        $this->em->persist($blog);
        $this->em->flush();
    }

    public function remove(Blog $blog)
    {
        $this->em->remove($blog);
        $this->em->flush();
    }

    public function blogOfId($id)
    {
        return $this->em->getRepository($this->blogClass)
            ->find($id);
    }
}