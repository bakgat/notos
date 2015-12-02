<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 14:55
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\Model\Resource\Book;
use Bakgat\Notos\Domain\Model\Resource\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization)
    {

        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b, a, p')
            ->from($this->bookClass, 'b')
            ->join('b.organization', 'o')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.publishers', 'p')
            ->where(
                $qb->expr()->eq('b.organization', ':org')
            )
            ->setParameter('org', $organization->id());

        return $query->getQuery()->getResult();
    }

    /**
     * @param Book $book
     * @return mixed|void
     */
    public function add(Book $book)
    {
        $this->em->persist($book);
        $this->em->flush();
    }

    /**
     * @param Book $book
     * @return mixed|void
     */
    public function update(Book $book)
    {
        $this->em->persist($book);
        $this->em->flush();
    }

    /**
     * @param $id
     * @return Book
     */
    public function bookOfId($id)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b, a, p')
            ->from($this->bookClass, 'b')
            ->leftJoin('b.authors', 'a')
            ->leftJoin('b.publishers', 'p')
            ->where(
                $qb->expr()->eq('b.id', '?1')
            )
            ->setParameter(1, $id);
        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @param Organization $organization
     * @param Party $author
     * @return ArrayCollection
     */
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

    /**
     * @param Organization $organization
     * @param Party $publisher
     * @return ArrayCollection
     */
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

    /**
     * @param Organization $organization
     * @param Isbn $isbn
     * @return Book
     */
    public function bookOfIsbn(Organization $organization, Isbn $isbn)
    {
        $qb = $this->em->createQueryBuilder();
        $query = $qb->select('b')
            ->from($this->bookClass, 'b')
            ->where(
                $qb->expr()->eq('b.organization', '?1'),
                $qb->expr()->eq('b.isbn', '?2')
            )
            ->setParameter(1, $organization->id())
            ->setParameter(2, $isbn->toString());
        return $query->getQuery()->getOneOrNullResult();
    }


    /**
     * @param Book $book
     * @return Book
     */
    public function clearAuthors(Book $book)
    {
        $book->clearAuthors();
        $this->em->persist($book);
        $this->em->flush();
        return $book;
    }

    /**
     * @param Book $book
     * @return Book
     */
    public function clearPublishers(Book $book)
    {
        $book->clearPublishers();
        $this->em->persist($book);
        $this->em->flush();
        return $book;

    }

    /**
     * @param Book $book
     * @return Book
     */
    public function clearTags(Book $book)
    {
        $book->clearTags();
        $this->em->persist($book);
        $this->em->flush();
        return $book;

    }
}