<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/10/15
 * Time: 17:15
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\DomainNameIsUnique;
use Bakgat\Notos\Domain\Model\Identity\DomainNameSpecification;
use Mockery\MockInterface;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class DomainNameIsUniqueTest extends TestCase
{
    /** @var  MockInterface $orgRepo */
    private $orgRepo;
    /** @var DomainNameSpecification $spec */
    private $spec;

    public function setUp()
    {
        $this->orgRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\OrganizationRepository');
        $this->spec = new DomainNameIsUnique($this->orgRepo);
    }

    /**
     * @test
     * @group username
     */
    public function should_return_true_if_unique()
    {
        $this->orgRepo->shouldReceive('organizationOfDomain')->andReturnNull();
        $this->assertTrue($this->spec->isSatisfiedBy(new DomainName('klimtoren.be')));
    }

    /**
     * @test
     * @group username
     */
    public function should_return_false_if_not_unique()
    {
        $this->orgRepo->shouldReceive('organizationOfDomain')->andReturn(['id' => 1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new DomainName('not_unique.be')));
    }
}