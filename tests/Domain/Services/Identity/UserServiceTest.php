<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 20/10/15
 * Time: 16:57
 */

namespace Bakgat\Notos\Tests\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\ACL\Role;
use Bakgat\Notos\Domain\Model\ACL\UserRole;
use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Services\Identity\UserService;
use Mockery as m;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

class UserServiceTest extends TestCase
{
    /** @var MockInterface $userRepo */
    private $userRepo;
    /** @var MockInterface */
    private $orgRepo;
    /** @var MockInterface */
    private $roleRepo;
    /** @var MockInterface */
    private $userRoleRepo;
    /** @var MockInterface */
    private $hasher;

    /** @var UserService $userService */
    private $userService;
    /** @var User $user */
    private $user;
    /** @var Organization $organization */
    private $organization;

    public function setUp()
    {
        parent::setUp();

        $this->userRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\UserRepository');
        $this->orgRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\OrganizationRepository');
        $this->roleRepo = m::mock('Bakgat\Notos\Domain\Model\ACL\RoleRepository');
        $this->userRoleRepo = m::mock('Bakgat\Notos\Domain\Model\ACL\UserRolesRepository');
        $this->roleRepo = m::mock('Bakgat\Notos\Domain\Model\ACL\RoleRepository');
        $this->hasher = m::mock('Illuminate\Contracts\Hashing\Hasher');
        $this->userService = new UserService($this->userRepo, $this->orgRepo,
            $this->roleRepo, $this->userRoleRepo,
            $this->hasher);

        //USER
        $fname = new Name('Karl');
        $lname = new Name('Van Iseghem');
        $uname = new Username('karl.vaniseghem@klimtoren.be');
        $pwd = new HashedPassword('password');
        $gender = new Gender(Gender::MALE);
        $email = new Email($uname->toString());

        $this->user = User::register($fname, $lname, $uname, $pwd, $email, $gender);

        //ORGANIZATION
        $orgName = new Name('VBS De Klimtoren');
        $domainName = new DomainName('klimtoren.be');

        $this->organization = Organization::register($orgName, $domainName);

        //ACL
        $role_admin = Role::register('admin');
        $role_sa = Role::register('sa');
        UserRole::register($this->user, $role_admin, $this->organization);
        UserRole::register($this->user, $role_sa, $this->organization);

    }

    /**
     * @test
     * @group userservice
     */
    public function should_get_users_if_organization_found()
    {
        $orgId = 1;

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturn($this->organization);

        $this->userRepo->shouldReceive('all')
            ->andReturn([['id' => 1], ['id' => 2]]);

        $users = $this->userService->getUsers($orgId);
        $this->assertCount(2, $users);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_org_not_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException');

        $orgId = 1;

        $this->orgRepo->shouldReceive('organizationOfId')
            ->with($orgId)
            ->andReturnNull();

        $users = $this->userService->getUsers($orgId);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_return_user_by_id()
    {
        $userId = 1;
        $this->userRepo->shouldReceive('userOfId')
            ->andReturn($this->user);

        $r_user = $this->userService->userOfId($userId);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $r_user);
        $this->assertEquals($this->user->username(), $r_user->username());
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_user_not_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\UserNotFoundException');

        $userId = 2;
        $this->userRepo->shouldReceive('userOfId')
            ->andReturnNull();

        $this->userService->userOfId($userId);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_return_user_by_username()
    {

        $username = 'karl.vaniseghem@klimtoren.be';
        $this->userRepo->shouldReceive('userOfUsername')
            ->andReturn($this->user);

        $r_user = $this->userService->userOfUsername($username);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $r_user);
        $this->assertEquals($this->user->username(), $r_user->username());
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_user_not_found_by_username()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\UserNotFoundException');

        $username = 'karl.vaniseghem@klimtoren.be';
        $this->userRepo->shouldReceive('userOfUsername')
            ->andReturnNull();

        $this->userService->userOfUsername($username);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_get_user_with_acls()
    {
        $userId = 1;
        $orgId = 2;

        $this->orgRepo->shouldReceive('organizationOfId')
            ->andReturn($this->organization);
        $this->userRepo->shouldReceive('userOfId')
            ->andReturn($this->user);

        $this->userRepo->shouldReceive('userWithACL')
            ->andReturn($this->user);

        /** @var User $r_user */
        $r_user = $this->userService->getUserWithACL($userId, $orgId);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $r_user);

        //TODO how is UserRole added to a user?
        //so we can test that here
       // $this->assertCount(2, $r_user->getRoles());
    }
}