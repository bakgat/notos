<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 11/11/15
 * Time: 10:12
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Resource\BookRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Resource\BookDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class BookDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var BookRepository $bookRepo */
    private $bookRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();

        $this->bookRepo = new BookDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);
    }

    /**
     * @test
     * @group bookrepo
     */
    public function should_return_5_books()
    {
        $klimtoren = $this->getKlimtoren();
        $books = $this->bookRepo->all($klimtoren);

        $this->assertCount(5, $books);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Book', $books[0]);
        $this->assertEquals('book 1', $books[0]->name());
    }

    /**
     * @test
     * @group bookrepo
     */
    public function should_return_empty_set_when_no_books_found()
    {

        $n_foo = new Name('foo');
        $dn_bar = new DomainName('bar.be');
        $foo = Organization::register($n_foo, $dn_bar);

        $books = $this->bookRepo->all($foo);
        $this->assertEmpty($books);
    }

    /**
     * @test
     * @group bookrepo
     */
    public function should_return_book_of_id()
    {
        $klimtoren = $this->getKlimtoren();
        $books = $this->bookRepo->all($klimtoren);
        $tmp = $books[0];
        $id = $tmp->id();

        $this->em->clear();

        $book = $this->bookRepo->bookOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Book', $book);
        $this->assertEquals('book 1', $book->name()->toString());
    }

    /**
     * @test
     * @group bookrepo
     */
    public function should_return_null_when_book_of_id_not_found()
    {
        $id = 9999999999;

        $book = $this->bookRepo->bookOfId($id);
        $this->assertNull($book);
    }

    /**
     * @test
     * @group bookrepo
     */
    public function should_return_book_of_isbn()
    {
        $isbn = new Isbn('9782123456803');
        $klimtoren = $this->getKlimtoren();

        $book = $this->bookRepo->bookOfIsbn($klimtoren, $isbn);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Book', $book);
        $this->assertEquals('book 1', $book->name()->toString());
    }

    /**
     * @test
     * @group bookrepo
     */
    public function should_return_null_when_book_of_isbn_not_found()
    {
        $isbn = new Isbn('9788879116190');
        $klimtoren = $this->getKlimtoren();

        $book = $this->bookRepo->bookOfIsbn($klimtoren, $isbn);

        $this->assertNull($book);
    }



    //TODO add update methods
    //TODO books of authors
    //TODO books of publishers

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function getKlimtoren()
    {
        $dn = new DomainName('klimtoren.be');
        $klimtoren = $this->orgRepo->organizationOfDomain($dn);
        return $klimtoren;
    }
}
