<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 11/11/15
 * Time: 10:12
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Resource;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
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
