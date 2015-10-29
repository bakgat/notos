<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 21/06/15
 * Time: 20:18
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\UserDoctrineORMRepository;
use Bakgat\Notos\Tests\EmTestCase;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\ACLFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\OrganizationFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\PartyRelationFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\UserFixtures;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class UserDoctrineORMRepositoryTest extends EmTestCase
{
    /** @var  UserDoctrineORMRepository $repository */
    private $repository;

    /** @var OrganizationDoctrineORMRepository $orgRepo */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();

        $this->repository = new UserDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);
        $this->loader->addFixture(new UserFixtures);
        $this->loader->addFixture(new OrganizationFixtures);
        $this->loader->addFixture(new PartyRelationFixtures);
        $this->loader->addFixture(new ACLFixtures);
    }

    /**
     * @test
     * @group user2
     */
    public function should_return_all_users_of_klimtoren()
    {
        $this->executor->execute($this->loader->getFixtures());
        $klimtoren = $this->orgRepo->organizationOfDomain(new DomainName('klimtoren.bez'));

        $users = $this->repository->all($klimtoren);

        $this->assertCount(2, $users);
    }

    /**
     * @test
     * @group user
     */
    public function should_find_user_by_username()
    {

        $this->executor->execute($this->loader->getFixtures());
        $username = new Username('karl.vaniseghem@klimtoren.bez');

        $user = $this->repository->userOfUsername($username);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
        $this->assertEquals($username, $user->username());
    }

    /**
     * @test
     * @group user
     */
    public function should_add_new_user()
    {
        $firstName = new Name('Ulrike');
        $lastName = new Name('Drieskens');
        $username = new Username('ulrike.drieskens@gmail.com');
        $email = new Email('ulrike.drieskens@gmail.com');
        $password = new HashedPassword(md5('password'));
        $gender = new Gender('F');

        $user = User::register($firstName, $lastName, $username, $password, $email, $gender);

        $this->repository->add($user);

        $this->em->clear();

        $user = $this->repository->userOfUsername(new Username('ulrike.drieskens@gmail.com'));

        $this->assertEquals($firstName, $user->firstName());
        $this->assertEquals($username, $user->username());
    }

    /**
     * @test
     * @group user
     */
    public function should_update_user()
    {
        $this->executor->execute($this->loader->getFixtures());
        $user = $this->repository->userOfUsername(new Username('karl.vaniseghem@klimtoren.bez'));

        $username = new Username('nieuwe.gebruikersnaam@klimtoren.bez');
        $user->updateUsername($username);
        $this->repository->update($user);

        $this->em->clear();

        $user = $this->repository->userOfUsername($username);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
        $this->assertEquals($username, $user->username());

    }

    /**
     * @test
     * @group user
     */
    public function should_lock_and_unlock_user()
    {
        $this->executor->execute($this->loader->getFixtures());

        $username = new Username('karl.vaniseghem@klimtoren.bez');
        $user = $this->repository->userOfUsername($username);

        $user->lock();
        $this->repository->update($user);

        $this->em->clear();

        $user = $this->repository->userOfUsername($username);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
        $this->assertTrue($user->locked());

        $user->unlock();

        $this->repository->update($user);

        $this->em->clear();

        $user = $this->repository->userOfUsername($username);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
        $this->assertFalse($user->locked());
    }


}
