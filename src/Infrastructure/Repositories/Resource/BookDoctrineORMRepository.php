<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 14:55
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\Model\Resource\Book;
use Bakgat\Notos\Domain\Model\Resource\BookRepository;
use Doctrine\ORM\EntityManager;

class BookDoctrineORMRepository implements BookRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $bookClass */
    private $bookClass;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->bookClass = 'Bakgat\Notos\Domain\Model\Resource\Book';
    }

    public function all(Organization $organization)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b, a, p')
            ->from($this->bookClass, 'b')
            ->join('b.organization', 'o')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.publishers', 'p');

        return $query->getQuery()->getResult();
    }

    public function add(Book $book)
    {
        // TODO: Implement add() method.
    }

    public function update(Book $book)
    {
        // TODO: Implement update() method.
    }

    public function bookOfId($id)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b, a, p, o')
            ->from($this->bookClass, 'b')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.publishers', 'p')
            ->where(
                $qb->expr()->eq('b.id', '?1')
            )
            ->setParameter(1, $id);
        return $query->getQuery()->getSingleResult();
    }

    public function booksOfAuthor(Organization $organization, Party $author)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b')
            ->from($this->bookClass, 'b')
            ->join('b.authors', 'a')
            ->where(
                $qb->expr()->eq('a.id', '?1'),
                $qb->expr()->eq('b.organization', '?2')
            )
            ->setParameter(1, $author->id())
            ->setParameter(2, $organization->id());
        return $query->getQuery()->getResult();
    }

    public function booksOfPublisher(Organization $organization, Party $publisher)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b')
            ->from($this->bookClass, 'b')
            ->join('b.publishers', 'p')
            ->where(
                $qb->expr()->eq('p.id', '?1')
            )
            ->setParameter(1, $publisher->id());
        return $query->getQuery()->getResult();
    }
}