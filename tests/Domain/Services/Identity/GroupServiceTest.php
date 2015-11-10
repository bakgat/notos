<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 10/11/15
 * Time: 15:28
 */

namespace Bakgat\Notos\Tests\Domain\Services\Identity;


use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Kind;
use Bakgat\Notos\Domain\Services\Identity\GroupService;
use Doctrine\Common\Collections\ArrayCollection;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;

use Mockery as m;

class GroupServiceTest extends TestCase
{
    /** @var MockInterface $kindRepo */
    private $kindRepo;
    /** @var MockInterface $groupRepo */
    private $groupRepo;

    /** @var GroupService $groupService */
    private $groupService;

    public function setUp()
    {
        parent::setUp();

        $this->groupRepo = m::mock('Bakgat\Notos\Domain\Model\Identity\GroupRepository');
        $this->kindRepo = m::mock('Bakgat\Notos\Domain\Model\KindRepository');

        $this->groupService = new GroupService($this->kindRepo, $this->groupRepo);
    }

    /**
     * @test
     * @group groupservice
     */
    public function should_return_groups_of_kind()
    {
        $kind = new Kind('level');
        $collection = new ArrayCollection();
        $i=0;
        while($i < 3) {
            $group = Group::register(new Name('group ' . ++$i));
            $group->setKind($kind);
            $collection->add($group);
        }

        $this->kindRepo->shouldReceive('get')
            ->with('level')
            ->andReturn($kind);
        $this->groupRepo->shouldReceive('groupsOfKind')
            ->with($kind)
            ->andReturn($collection);

        $groups = $this->groupService->groupsOfKind('level');

        $this->assertCount(3, $groups);
        $this->assertEquals('group 1', $groups[0]->name()->toString());
    }
}
