<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 23/10/15
 * Time: 14:47
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Model\Identity\UserRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\UserDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;
use Bakgat\Notos\Tests\Fixtures\UserFixtures;
use Mockery as m;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class UserDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var OrganizationRepository $organization */
    private $orgRepo;
    /** @var UserRepository $userRepo */
    private $userRepo;


    public function setUp()
    {
        parent::setUp();

        $this->userRepo = new UserDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);

        $this->loader->addFixture(new UserFixtures());

        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_user_with_id_1()
    {
        $karl = $this->getUser('karl.vaniseghem@klimtoren.be');
        $id = $karl->id();
        $this->em->clear();

        $user = $this->userRepo->userOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
        $this->assertTrue($user->username()->equals($karl->username()));
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_null_when_not_found()
    {
        $user = $this->userRepo->userOfId(99999999);
        $this->assertNull($user);
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_user_by_its_username()
    {
        $username = new Username('karl.vaniseghem@klimtoren.be');
        $user = $this->getUser('karl.vaniseghem@klimtoren.be');

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
        $this->assertTrue($user->username()->equals($username));
        $this->assertEquals('Karl', $user->firstName());
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_2_users_in_klimtoren()
    {
        $klimtoren = $this->getOrg('klimtoren.be');
        $users = $this->userRepo->all($klimtoren);

        $this->assertCount(2, $users);
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_1_user_in_wassenaard()
    {
        $wassenaard = $this->getOrg('wassenaard.be');
        $users = $this->userRepo->all($wassenaard);

        $this->assertCount(1, $users);
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_have_empty_users_when_foo_organization_found()
    {
        $n_foo = new Name('foo');
        $dn_foo = new DomainName('bar.be');

        $organization = new Organization($n_foo, $dn_foo);
        $users = $this->userRepo->all($organization);
        $this->assertEmpty($users);
    }


    /* ***************************************************
     * private methods
     * **************************************************/
    public function getUser($username)
    {
        $username = new Username($username);
        $user = $this->userRepo->userOfUsername($username);
        return $user;
    }

    public function getOrg($domainName)
    {
        $dn = new DomainName($domainName);
        $organization = $this->orgRepo->organizationOfDomain($dn);
        return $organization;
    }


}