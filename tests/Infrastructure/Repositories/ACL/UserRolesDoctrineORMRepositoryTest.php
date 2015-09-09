<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 11:26
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\ACL;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Infrastructure\Repositories\ACL\UserRolesDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\UserDoctrineORMRepository;
use Bakgat\Notos\Test\EmTestCase;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\ACLFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\OrganizationFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\UserFixtures;

class UserRolesDoctrineORMRepositoryTest extends EmTestCase
{
    /** @var  UserRolesDoctrineORMRepository */
    private $urRepo;
    /** @var  UserDoctrineORMRepository */
    private $userRepo;
    /** @var  OrganizationDoctrineORMRepository $orgRepo */
    private $orgRepo;


    public function setUp()
    {
        parent::setUp();

        $this->urRepo = new UserRolesDoctrineORMRepository($this->em);
        $this->userRepo = new UserDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);
        $this->loader->addFixture(new UserFixtures);
        $this->loader->addFixture(new OrganizationFixtures);
        $this->loader->addFixture(new ACLFixtures);
    }

    /**
     * @test
     * @group userRoles
     */
    public function should_find_roles_of_user()
    {
        $this->executor->execute($this->loader->getFixtures());

        $karl = $this->userRepo->userOfUsername(new Username('karl.vaniseghem@klimtoren.bez'));
        $klimtoren = $this->orgRepo->organizationOfDomain(new DomainName('klimtoren.bez'));
        $wassenaard = $this->orgRepo->organizationOfDomain(new DomainName('wassenaard.bez'));

        $roles = $this->urRepo->rolesOfUser($karl, $klimtoren);
        $this->assertCount(2, $roles);

        $roles = $this->urRepo->rolesOfUser($karl, $wassenaard);
        $this->assertCount(1, $roles);

    }

}
