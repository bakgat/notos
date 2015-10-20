<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:15
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Isbn;
use Bakgat\Notos\Domain\Model\Identity\IsbnIsUnique;
use Bakgat\Notos\Domain\Model\Identity\IsbnSpecification;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class IsbnIsUniqueTest extends TestCase
{
    /** @var  MockInterface $bookRepo */
    private $bookRepo;
    /** @var IsbnSpecification $spec */
    private $spec;
    /** @var Organization $org */
    private $org;

    public function setUp()
    {
        parent::setUp();
        $this->bookRepo = m::mock('Bakgat\Notos\Domain\Model\Resource\BookRepository');
        $this->org = Organization::register(new Name('VBS De Klimtoren'), new DomainName('klimtoren.be'));
        $this->spec = new IsbnIsUnique($this->bookRepo);
    }

    /**
     * @test
     * @group isbn
     */
    public function should_return_true_if_unique()
    {
        $this->bookRepo->shouldReceive('bookOfIsbn')->andReturnNull();
        $this->assertTrue($this->spec->isSatisfiedBy(new Isbn('978-3-16-148410-0'), $this->org));
    }

    /**
     * @test
     * @group isbn
     */
    public function should_return_false_if_not_unique()
    {
        $this->bookRepo->shouldReceive('bookOfIsbn')->andReturn(['id' => 1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new Isbn('978-3-16-148410-0'), $this->org));
    }
}