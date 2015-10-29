<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/10/15
 * Time: 21:40
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\ACL;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Tests\DoctrineTestCase;
use Mockery as m;
use Mockery\MockInterface;

class UserRolesRepoTest extends DoctrineTestCase
{
    /** @var User $user */
    private $user;
    /** @var Organization $organization*/
    private $organization;

    public function setUp()
    {
        parent::setUp();

    }

    public function should_create_roles_of_user_query()
    {
    }
}