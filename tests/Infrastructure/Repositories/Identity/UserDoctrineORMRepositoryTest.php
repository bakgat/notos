<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 23/10/15
 * Time: 14:47
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
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
    public function should_return_user_with_id()
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
    public function should_return_null_when_username_not_found()
    {
        $username = new Username('foo@bar.be');
        $user = $this->userRepo->userOfUsername($username);

        $this->assertNull($user);
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

    /**
     * @test
     * @group userrepo
     */
    public function should_have_2_roles_for_karl_in_klimtoren()
    {
        $username = new Username('karl.vaniseghem@klimtoren.be');
        $klimtoren = $this->getOrg('klimtoren.be');

        $user = $this->userRepo->userWithACL($username, $klimtoren);

        $this->assertCount(2, $user->getRoles());
        $this->assertTrue(in_array('sa', $user->getRoles()));
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_have_3_roles_for_rebekka_in_klimtoren()
    {
        $username = new Username('rebekka.buyse@klimtoren.be');
        $klimtoren = $this->getOrg('klimtoren.be');

        $user = $this->userRepo->userWithACL($username, $klimtoren);

        $this->assertCount(3, $user->getRoles());
        $this->assertTrue(in_array('book_manager', $user->getRoles()));
        $this->assertFalse(in_array('website_manager', $user->getRoles()));
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_null_when_username_with_acl_not_found()
    {
        $username = new Username('foo@bar.be');
        $klimtoren = $this->getOrg('klimtoren.be');

        $user = $this->userRepo->userWithACL($username, $klimtoren);
        $this->assertNull($user);
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_null_when_org_with_acl_not_found()
    {
        $username = new Username('karl.vaniseghem@klimtoren.be');
        $n_foo = new Name('foo');
        $dn_foo = new DomainName('bar.be');
        $foo = Organization::register($n_foo, $dn_foo);

        $user = $this->userRepo->userWithACL($username, $foo);
        $this->assertNull($user);
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_2_organizations_for_karl()
    {
        $user = $this->getUser('karl.vaniseghem@klimtoren.be');
        $organizations = $this->userRepo->organizationsOfUser($user);

        $this->assertCount(2, $organizations);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Organization', $organizations[0]);
    }

    /**
     * @test
     * @group userrepo
     */
    public function should_return_null_organizations_for_foo()
    {
        $fn_foo = new Name('foo');
        $ln_foo = new Name('bar');
        $un_foo = new Username('foo@bar.be');
        $e_foo = new Email($un_foo->toString());
        $pwd_foo = new HashedPassword(bcrypt('password'));
        $g_foo = new Gender(Gender::OTHER);

        $foo = User::register($fn_foo, $ln_foo, $un_foo, $pwd_foo, $e_foo, $g_foo);
        $organizations = $this->userRepo->organizationsOfUser($foo);

        $this->assertEmpty($organizations);
    }

    //TODO add and update tests
    //TODO by email test

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