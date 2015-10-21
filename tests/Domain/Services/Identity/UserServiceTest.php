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
    /** @var Role $roleUser */
    private $roleUser;
    /** @var array $userData */
    private $userData;

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
        $pwd = new HashedPassword(bcrypt('password'));
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
        $this->roleUser = Role::register('user');
        UserRole::register($this->user, $role_admin, $this->organization);
        UserRole::register($this->user, $role_sa, $this->organization);

        $this->userData = [
            'first_name' => 'Karl',
            'last_name' => 'Van Iseghem',
            'username' => 'karl.vaniseghem@klimtoren.be',
            'password' => 'password',
            'gender' => 'M'
        ];
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

    /**
     * @test
     * @group userservice
     */
    public function should_get_profile()
    {

    }

    /**
     * @test
     * @group userservice
     */
    public function should_add_valid_data()
    {
        //test without reset_email
        $orgId = 1;

        /* GET THE ORGANIZATION */
        $this->orgRepo->shouldReceive('organizationOfId')
            ->andReturn($this->organization);

        /* CHECK USERNAME DUPLICATES */
        $this->userRepo->shouldReceive('userOfUsername')
            ->andReturnNull();

        /* GET THE ROLE WITH NAME 'user' */
        $this->roleRepo->shouldReceive('get')
            ->andReturn($this->roleUser);

        /* THE USERROLE WILL BE REGISTERED */
        $this->userRoleRepo->shouldReceive('register');

        /* GET PROFILE */
        $this->userRepo->shouldReceive('userOfId')
            ->andReturn($this->user);
        $this->userRepo->shouldReceive('userWithACL')
            ->andReturn($this->user);
        $this->userRepo->shouldReceive('organizationsOfUser')
            ->andReturn([$this->organization]);
        //END GET PROFILE

        $this->userRepo->shouldReceive('add')
            ->andReturn($this->user);

        $r_user = $this->userService->add($this->userData, $orgId);

        $this->assertEquals($this->userData['username'], $r_user->getEmailForPasswordReset());
        //$this->assertEquals(bcrypt($this->userData['password']), $r_user->getAuthPassword());
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_no_org_found_when_adding_user()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException');

        $this->orgRepo->shouldReceive('organizationOfId')
            ->andReturnNull();

        $orgId = 233;

        $this->userService->add($this->userData, $orgId);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_not_unqiue_username_when_adding_user()
    {
        $this->setExpectedException('Bakgat\Notos\Exceptions\DuplicateException');

        //test without reset_email
        $orgId = 233;

        /* GET THE ORGANIZATION */
        $this->orgRepo->shouldReceive('organizationOfId')
            ->andReturn($this->organization);

        /* CHECK USERNAME DUPLICATES */
        $this->userRepo->shouldReceive('userOfUsername')
            ->andReturn($this->user);

        $this->userService->add($this->userData, $orgId);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_precondition_failed_on_faulty_username()
    {
        $this->setExpectedException('Bakgat\Notos\Exceptions\PreconditionFailedException');

        //test without reset_email
        $orgId = 233;

        $c_data = $this->userData;
        $c_data['username'] = 'karl'; //must be e-mail format instead
        $this->userService->add($c_data, $orgId);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_precondition_failed_on_faulty_gender()
    {
        $this->setExpectedException('Bakgat\Notos\Exceptions\PreconditionFailedException');

        //test without reset_email
        $orgId = 233;

        $c_data = $this->userData;
        $c_data['gender'] = 'k'; //must be Gender format (m, f, o,...) instead
        $this->userService->add($c_data, $orgId);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_throw_user_not_found_on_update()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\Identity\Exceptions\UserNotFoundException');

        $this->userRepo->shouldReceive('userOfId')
            ->andReturnNull();

        $c_data = $this->userData;
        $c_data['id'] = -1;

        $this->userService->update($c_data);
    }

    /**
     * @test
     * @group userservice
     */
    public function should_update_valid_data() {
        $n_data = [
            'id' => 1,
            'first_name' => 'Ulrike',
            'last_name' => 'Drieskens',
            'gender' => 'f'
        ];

        $this->userRepo->shouldReceive('userOfId')
            ->andReturn($this->user);

        $this->userRepo->shouldReceive('update');

        $r_user = $this->userService->update($n_data);

        //new firstname and gender
        $this->assertEquals($n_data['first_name'], $r_user->firstName()->toString());
        $this->assertEquals(strtoupper($n_data['gender']), $r_user->gender()->toString());

        //username was not updated
        $this->assertEquals($this->user->username(), $r_user->username());
    }

    //TODO: make and test destroy method

    /**
     * @test
     * @group userservice
     */
    public function should_reset_password() {
        $n_pwd = 'new_password';
        $n_hashed = bcrypt($n_pwd);

        $this->hasher->shouldReceive('make')
            ->andReturn($n_hashed);
        $this->userRepo->shouldReceive('update');

        $r_user = $this->userService->resetPassword($this->user, $n_pwd);

        $this->assertEquals($n_hashed, $r_user->getAuthPassword());
    }
}