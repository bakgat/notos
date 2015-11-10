<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 2/11/15
 * Time: 08:47
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\ACL;


use Bakgat\Notos\Domain\Model\ACL\RoleRepository;
use Bakgat\Notos\Infrastructure\Repositories\ACL\RoleDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class RoleDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var RoleRepository $roleRepo */
    private $roleRepo;

    public function setUp()
    {
        parent::setUp();

        $this->roleRepo = new RoleDoctrineORMRepository($this->em);

    }

    /**
     * @test
     * @group rolerepo
     */
    public function should_return_role()
    {
        $role = $this->roleRepo->get('sa');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\ACL\Role', $role);
    }

    /**
     * @test
     * @group rolerepo
     */
    public function should_throw_role_not_found()
    {
        $this->setExpectedException('Bakgat\Notos\Domain\Model\ACL\Exceptions\RoleNotFoundException');

        $role = $this->roleRepo->get('foo');
    }
}
