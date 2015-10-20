<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:15
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\IsbnIsUnique;
use Bakgat\Notos\Domain\Model\Identity\IsbnSpecification;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class IsbnIsUniqueTest extends TestCase
{
    /** @var  MockInterface $bookRepo */
    private $bookRepo;
    /** @var IsbnSpecification $spec */
    private $spec;

    public function setUp()
    {
        $this->bookRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\BookRepository');
        $this->spec = new IsbnIsUnique($this->bookRepo);
    }

    /**
     * @test
     * @group isbn
     */
    public function should_return_true_if_unique()
    {
        $this->bookRepo->shouldReceive('bookOfIsbn')->andReturnNull();
        $this->assertTrue($this->spec->isSatisfiedBy(new Isbn('9789027439642')));
    }

    /**
     * @test
     * @group isbn
     */
    public function should_return_false_if_not_unique()
    {
        $this->bookRepo->shouldReceive('bookOfIsbn')->andReturn(['id' => 1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new Isbn('9789027439642')));
    }
}