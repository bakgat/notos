<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/06/15
 * Time: 15:59
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Infrastructure\Repositories\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Tests\EmTestCase;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\OrganizationFixtures;

use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class OrganizationDoctrineORMRepositoryTest extends EmTestCase
{
    /** @var  OrganizationDoctrineORMRepository */
    private $repository;


    public function setUp()
    {
        parent::setUp();

        $this->repository = new OrganizationDoctrineORMRepository($this->em);
        $this->loader->addFixture(new OrganizationFixtures);
    }

    /** @test */
    public function should_find_organization_by_domainname()
    {
        $this->executor->execute($this->loader->getFixtures());

        $domain_name = new DomainName('klimtoren.bez');
        $org = $this->repository->organizationOfDomain($domain_name);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Organization', $org);
        $this->assertEquals($domain_name, $org->domainName());
    }

    /** @test */
    public function should_add_new_organization()
    {
        $name = new Name('VBS De Wassenaard');
        $domain_name = new DomainName('dewassenaard.bez');
        $org = new Organization($name);
        $org->setDomainName($domain_name);

        $this->repository->add($org);
        $this->em->clear();

        $org = $this->repository->organizationOfDomain(new DomainName('dewassenaard.bez'));

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Organization', $org);
        $this->assertEquals($org->domainName(), $domain_name);

    }


}
