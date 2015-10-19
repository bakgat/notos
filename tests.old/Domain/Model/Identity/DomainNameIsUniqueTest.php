<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 14:19
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;

use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\DomainNameIsUnique;
use Bakgat\Notos\Domain\Model\Identity\DomainNameSpecification;
use Mockery as m;
use Orchestra\Testbench\TestCase;

class DomainNameIsUniqueTest extends TestCase
{
    private $orgRepo;
    /** @var DomainNameSpecification $spec */
    private $spec;

    public function setUp() {
        $this->orgRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\OrganizationRepository');
        $this->spec = new DomainNameIsUnique($this->orgRepo);
    }

    /**
     * @test
     * @group Specification
     */
    public function should_return_true_when_unique() {
        $this->orgRepo->shouldReceive('organizationOfDomain')->andReturn(null);
        $this->assertTrue($this->spec->isSatisfiedBy(new DomainName('unique.be')));
    }
    /**
     * @test
     * @group Specification
     */
    public function should_return_false_when_not_unique() {
        $this->orgRepo->shouldReceive('organizationOfDomain')->andReturn(['id'=>1]);
        $this->assertFalse($this->spec->isSatisfiedBy(new DomainName('klimtoren.be')));
    }
}