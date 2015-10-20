<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:15
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainNameSpecification;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Location\URLIsUnique;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class URLIsUniqueTest extends TestCase
{
    /** @var  MockInterface $webRepo */
    private $webRepo;
    /** @var DomainNameSpecification $spec */
    private $spec;

    public function setUp()
    {
        $this->webRepo = m::mock('Bakgat\Notos\Domain\Model\Location\WebsitesRepository');
        $this->spec = new URLIsUnique($this->webRepo);
    }

    /**
     * @test
     * @group url
     */
    public function should_return_true_if_unique()
    {
        $this->webRepo->shouldReceive('websiteOfURL')->andReturnNull();
        $this->assertTrue($this->spec->isSatisfiedBy(new URL('www.unique.be')));
    }

    /**
     * @test
     * @group url
     */
    public function should_return_false_if_not_unique()
    {
        $this->webRepo->shouldReceive('websiteOfURL')->andReturn(['id' => 1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new URL('www.non_unique.be')));
    }
}