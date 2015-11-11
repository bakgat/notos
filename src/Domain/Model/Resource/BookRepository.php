<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 14:53
 */

namespace Bakgat\Notos\Domain\Model\Resource;


use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Doctrine\Common\Collections\ArrayCollection;

interface BookRepository
{
    /**
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization);

    /**
     * @param Book $book
     * @return mixed
     */
    public function add(Book $book);

    /**
     * @param Book $book
     * @return mixed
     */
    public function update(Book $book);

    /**
     * @param $id
     * @return Book
     */
    public function bookOfId($id);

    /**
     * @param Organization $organization
     * @param Party $author
     * @return ArrayCollection
     */
    public function booksOfAuthor(Organization $organization, Party $author);

    /**
     * @param Organization $organization
     * @param Party $publisher
     * @return ArrayCollection
     */
    public function booksOfPublisher(Organization $organization, Party $publisher);

    /**
     * @param Organization $organization
     * @param Isbn $isbn
     * @return Book
     */
    public function bookOfIsbn(Organization $organization, Isbn $isbn);
}