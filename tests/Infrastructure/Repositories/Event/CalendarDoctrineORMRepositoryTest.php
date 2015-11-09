<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 8/11/15
 * Time: 19:43
 */

namespace Bakgat\Notos\Tests\Infrastructure\Repositories\Event;


use Bakgat\Notos\Domain\Model\Event\CalendarRepository;
use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Infrastructure\Repositories\Event\CalendarDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\GroupDoctrineORMRepository;
use Bakgat\Notos\Infrastructure\Repositories\Identity\OrganizationDoctrineORMRepository;
use Bakgat\Notos\Tests\DoctrineTestCase;

class CalendarDoctrineORMRepositoryTest extends DoctrineTestCase
{
    /** @var CalendarRepository $calendarRepo */
    private $calendarRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;
    /** @var GroupRepository $groupRepo */
    private $groupRepo;

    public function setUp()
    {
        parent::setUp();

        $this->calendarRepo = new CalendarDoctrineORMRepository($this->em);
        $this->orgRepo = new OrganizationDoctrineORMRepository($this->em);
        $this->groupRepo = new GroupDoctrineORMRepository($this->em);

        $this->executor->execute($this->loader->getFixtures());
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_5_events()
    {
        $klimtoren = $this->getKlimtoren();
        $events = $this->calendarRepo->all($klimtoren);
        $this->assertCount(5, $events);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Event\CalendarEvent', $events[0]);
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_empty_set()
    {
        $n_foo = new Name('foo');
        $dn_foo = new DomainName('bar.be');
        $foo = Organization::register($n_foo, $dn_foo);

        $events = $this->calendarRepo->all($foo);
        $this->assertEmpty($events);
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_3_events_of_group_1KA()
    {
        $k1a = $this->get1KA();
        $events = $this->calendarRepo->eventsOfGroup($k1a);

        $this->assertCount(3, $events);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Event\CalendarEvent', $events[0]);
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_empty_set_of_group_foo()
    {
        $n_foo = new Name('foo');
        $foo = Group::register($n_foo);

        $events = $this->calendarRepo->eventsOfGroup($foo);
        $this->assertEmpty($events);
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_event_of_id()
    {
        $klimtoren = $this->getKlimtoren();
        $events = $this->calendarRepo->all($klimtoren);
        $tmp = $events[0];
        $id = $tmp->id();

        $this->em->clear();

        $event = $this->calendarRepo->eventOfId($id);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Event\CalendarEvent', $event);
        $this->assertEquals($id, $event->id());
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_null_when_event_of_id_not_found()
    {
        $id = 9999999999;
        $event = $this->calendarRepo->eventOfId($id);
        $this->assertNull($event);
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_5_events_between_in_past()
    {
        $klimtoren = $this->getKlimtoren();
        $start = date("Y-m-d H:i:s", 957182400); //1/5/2000
        $end = date("Y-m-d H:i:s", 1241179200); //1/5/2009
        $events = $this->calendarRepo->eventsBetween($klimtoren, $start, $end);

        $this->assertCount(5, $events);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Event\CalendarEvent', $events[0]);
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_10_events_overall_time()
    {
        $klimtoren = $this->getKlimtoren();
        $start = date("Y-m-d H:i:s", 957182400); //1/5/2000
        $end = date("Y-m-d H:i:s", 1515758400); //12/12/2018
        $events = $this->calendarRepo->eventsBetween($klimtoren, $start, $end);

        $this->assertCount(10, $events);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Event\CalendarEvent', $events[0]);
    }

    /**
     * @test
     * @group calendarrepo
     */
    public function should_return_empty_set_when_looking_too_far_in_future()
    {
        $klimtoren = $this->getKlimtoren();
        $start = date("Y-m-d H:i:s", 2007182400); //9/8/2033
        $end = date("Y-m-d H:i:s", 2015758400); //16/11/2033
        $events = $this->calendarRepo->eventsBetween($klimtoren, $start, $end);

        $this->assertEmpty($events);
    }

    //TODO: ADD UPDATE and REMOVE events tests

    /* ***************************************************
     * PRIVATE METHODS
     * **************************************************/
    private function getKlimtoren()
    {
        $dn = new DomainName('klimtoren.be');
        return $this->orgRepo->organizationOfDomain($dn);
    }

    private function get1KA()
    {
        $n_1KA = new Name('1KA');
        $group = $this->groupRepo->groupOfName($n_1KA);
        return $group;
    }
}
