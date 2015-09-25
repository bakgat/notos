<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 14:53
 */

namespace Bakgat\Notos\Domain\Model\Resource;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\Party;

interface BookRepository
{
    public function all(Organization $organization);

    public function add(Book $book);

    public function update(Book $book);

    public function bookOfId($id);

    public function booksOfAuthor(Organization $organization, Party $author);

    public function booksOfPublisher(Organization $organization, Party $publisher);
}