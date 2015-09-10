<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 9/09/15
 * Time: 14:45
 */

namespace Bakgat\Notos\Tests\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Username;
use Bakgat\Notos\Domain\Services\Identity\UserService;
use Bakgat\Notos\Infrastructure\Repositories\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\UserDoctrineORMRepository;
use Bakgat\Notos\Tests\EmTestCase;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\ACLFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\OrganizationFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\PartyRelationFixtures;
use Bakgat\Notos\Tests\Infrastructure\Repositories\Fixtures\UserFixtures;

use Mockery as m;

class UserServiceTest extends EmTestCase
{
    /** @var  UserService $service */
    private $service;
    /** @var  UserDoctrineORMRepository */
    private $userRepo;
    /** @var  OrganizationDoctrineORMRepository */
    private $orgRepo;

    public function setUp()
    {
        parent::setUp();



        $this->userRepo = new UserDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);

        $this->service = new UserService(
           //$this->getGuard(), //TODO
            $this->userRepo,
            $this->orgRepo
        );

        $this->loader->addFixture(new OrganizationFixtures);
        $this->loader->addFixture(new UserFixtures);
        $this->loader->addFixture(new PartyRelationFixtures);
        $this->loader->addFixture(new ACLFixtures);
    }

    /**
     * @test
     * @group userService
     */
    public function should_have_all_acls()
    {
        $this->executor->execute($this->loader->getFixtures());

        $username = new Username('karl.vaniseghem@klimtoren.bez');
        $domain = new DomainName('klimtoren.bez');

        $user = $this->service->getUserWithACL($username, $domain);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
        $this->assertCount(2, $user->userRoles());

        //user_roles must be loaded
        $userRole = $user->userRoles()[0];
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\ACL\UserRole', $userRole);
        //role must be loaded
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\ACL\Role', $userRole->role());

        $this->assertEquals($userRole->role()->slug(), 'admin');
    }


    protected function getGuard()
    {
        list($session, $provider, $request, $cookie) = $this->getMocks();
        return new \Illuminate\Auth\Guard($provider, $session, $request);
    }
    protected function getMocks()
    {
        return array(
            m::mock('Illuminate\Session\Store'),
            m::mock('Illuminate\Auth\UserProviderInterface'),
            \Symfony\Component\HttpFoundation\Request::create('/', 'GET'),
            m::mock('Illuminate\Cookie\CookieJar'),
        );
    }

}
