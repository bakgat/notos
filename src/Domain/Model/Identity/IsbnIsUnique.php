<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 28/06/15
 * Time: 11:52
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;


use Bakgat\Notos\Domain\Model\Resource\BookRepository;

class IsbnIsUnique implements IsbnSpecification {

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * Creates a new instance of the IsbnIsUnique specification
     *
     * @param BookRepository $repository
     */
    public function __construct(BookRepository $repository) {
        $this->repository = $repository;
    }


    /**
     * Check to see if the specification is satisfied
     *
     * @param Isbn $isbn
     * @return bool
     */
    public function isSatisfiedBy(Isbn $isbn)
    {
        $this->repository->bookOfIsbn($isbn);
    }
}