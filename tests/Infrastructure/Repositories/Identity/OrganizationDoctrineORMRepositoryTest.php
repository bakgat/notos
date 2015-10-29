<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 29/10/15
 * Time: 09:33
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;
use Bakgat\Notos\Tests\Fixtures\UserFixtures;
use Mockery as m;
use Mockery\MockInterface;

class OrganizationDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var OrganizationRepository $organization */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();

        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);

        $this->loader->addFixture(new UserFixtures);
        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group orgrepo
     */
    public function should_return_2_organizations()
    {
        $organizations = $this->orgRepo->all();
        $this->assertCount(2, $organizations);
    }

    /**
     * @test
     * @group orgrepo
     */
    public function should_return_org_by_its_domain()
    {
        $organization = $this->get_klimtoren();
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Organization', $organization);
        $this->assertEquals('klimtoren.be', $organization->domainName());
    }

    /**
     * @test
     * @group orgrepo
     */
    public function should_return_org_null_when_not_found_by_domain()
    {
        $fake_dn = new DomainName('foo.be');
        $organization = $this->orgRepo->organizationOfDomain($fake_dn);
        $this->assertNull($organization);
    }

    /**
     * @test
     * @group orgrepo
     */
    public function should_return_org_by_its_id()
    {
        $klimtoren = $this->get_klimtoren();
        $id = $klimtoren->id();

        $this->em->clear();

        $organization = $this->orgRepo->organizationOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Organization', $organization);
        $this->assertTrue($klimtoren->name()->equals($organization->name()));
    }

    /**
     * @test
     * @group orgrepo
     */
    public function should_return_org_null_when_not_found_by_id()
    {
        $organization = $this->orgRepo->organizationOfId(999999999);
        $this->assertNull($organization);
    }

    /* ***************************************************
     * private methods
     * **************************************************/
    private function get_klimtoren()
    {
        $dn = 'klimtoren.be';
        $organization = $this->orgRepo->organizationOfDomain($dn);
        return $organization;
    }

    //TODO: add and update tests
}