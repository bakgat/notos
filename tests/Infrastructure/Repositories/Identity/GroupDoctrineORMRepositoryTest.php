<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/10/15
 * Time: 06:16
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Identity;


use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Kind;
use Bakgat\Notos\Infrastructure\Repositories\Identity\GroupDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\KindCacheRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class GroupDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var GroupRepository $groupRepo */
    private $groupRepo;
    /** @var KindCacheRepository $kindRepo */
    private $kindRepo;

    public function setUp()
    {
        parent::setUp();

        $this->groupRepo = new GroupDoctrineORMRepository($this->em);
        $this->kindRepo = new KindCacheRepository($this->em);

        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group grouprepo
     */
    public function should_return_group_by_name()
    {
        $group = $this->groupRepo->groupOfName('1KA');
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Group', $group);
    }

    /**
     * @test
     * @group grouprepo
     */
    public function should_return_null_when_not_found_by_name()
    {
        $group = $this->groupRepo->groupOfName('foo');
        $this->assertNull($group);
    }

    /**
     * @test
     * @group grouprepo
     */
    public function should_return_groups_of_kind()
    {
        $level = $this->kindRepo->get('level');
        $classgroup = $this->kindRepo->get('classgroup');

        $levels = $this->groupRepo->groupsOfKind($level);
        $this->assertCount(3, $levels);

        $classgroups = $this->groupRepo->groupsOfKind($classgroup);
        $this->assertCount(1, $classgroups);
    }

    /**
     * @test
     * @group grouprepo
     */
    public function should_return_empty_when_no_groups_of_kind_are_found()
    {
        $foo = new Kind('foo');

        $groups = $this->groupRepo->groupsOfKind($foo);
        $this->assertEmpty($groups);
    }

    /**
     * @test
     * @group grouprepo
     */
    public function should_return_group_of_id()
    {
        $tmp = $this->groupRepo->groupOfName('OK');
        $id = $tmp->id();

        $this->em->clear();

        $group = $this->groupRepo->groupOfId($id);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Group', $group);
        $this->assertEquals($id, $group->id());
        $this->assertTrue($tmp->name()->equals($group->name()));
    }

    /**
     * @test
     * @group grouprepo
     */
    public function should_return_null_when_group_of_id_not_found()
    {
        $id = 9999999999;
        $group = $this->groupRepo->groupOfId($id);

        $this->assertNull($group);
    }
}
