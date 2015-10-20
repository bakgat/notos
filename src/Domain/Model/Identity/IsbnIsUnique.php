<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 28/06/15
 * Time: 11:52
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Resource\BookRepository;

class IsbnIsUnique implements IsbnSpecification {

    /**
     * @var BookRepository
     */
    private $repository;


    public function __construct(BookRepository $repository) {
        $this->repository = $repository;
    }


    /**
     * Check to see if the specification is satisfied
     *
     * @param Isbn $isbn
     * @return bool
     */
    public function isSatisfiedBy(Organization $organization, Isbn $isbn)
    {
        if(!$this->repository->bookOfIsbn($organization, $isbn)) {
            return true;
        }
        return false;
    }
}