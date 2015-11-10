<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 10/11/15
 * Time: 15:57
 */

namespace Bakgat\Notos\Tests\Domain\Services\Resource;


use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Resource\Book;
use Bakgat\Notos\Domain\Services\Resource\BookService;
use Bakgat\Notos\Tests\Domain\Services\TestDataTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery as m;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class BookServiceTest extends TestCase
{
    use TestDataTrait;

    /** @var MockInterface $bookRepo */
    private $bookRepo;
    /** @var MockInterface $orgRepo */
    private $orgRepo;
    /** @var BookService $bookService */
    private $bookService;


    public function setUp()
    {
        parent::setUp();

        $this->setupMocks();

        $this->bookService = new BookService($this->bookRepo, $this->orgRepo);

        $this->setupTestData();
    }

    /**
     * @test
     * @group bookservice
     */
    public function should_return_all_books_of_org()
    {
        $orgId = 1;

        $collection = new ArrayCollection();
        $i = 0;
        while ($i < 10) {
            $n_book = new Name('book ' . ++$i);
            $isbn = new Isbn('9789027439642');
            $book = Book::register($n_book, $isbn);
            $collection->add($book);
        }

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturn($this->klimtoren);

        $this->bookRepo->shouldReceive('all')
            ->with($this->klimtoren)
            ->andReturn($collection);

        $books = $this->bookService->all($orgId);
        $this->assertCount(10, $books);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Resource\Book', $books[0]);
    }

    /**
     * @test
     * @group bookservice
     */
    public function should_throw_org_not_found_when_request_all()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException');

        $orgId = 2;
        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturnNull();

        $books = $this->bookService->all($orgId);
    }

    /**
     * @test
     * @group bookservice
     */
    public function should_return_empty_set_when_no_books_are_found()
    {
        $orgId = 1;

        $collection = new ArrayCollection();

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturn($this->klimtoren);

        $this->bookRepo->shouldReceive('all')
            ->with($this->klimtoren)
            ->andReturn($collection);

        $books = $this->bookService->all($orgId);
        $this->assertEmpty($books);
    }

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function setupMocks()
    {
        $this->bookRepo = m::mock('Bakgat\Notos\Domain\Model\Resource\BookRepository');
        $this->orgRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\OrganizationRepository');
    }
}
