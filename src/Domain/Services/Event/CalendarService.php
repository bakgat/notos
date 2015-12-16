<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/09/15
 * Time: 14:31
 */

namespace Bakgat\Notos\Domain\Services\Event;


use Bakgat\Notos\Domain\Model\Event\CalendarEvent;
use Bakgat\Notos\Domain\Model\Event\CalendarRepository;
use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use \DateTime;

class CalendarService
{
    /** @var CalendarRepository $calendarRepo */
    private $calendarRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;
    /** @var GroupRepository $groupRepo */
    private $groupRepo;

    public function __construct(CalendarRepository $calendarRepository, OrganizationRepository $organizationRepository,
                                GroupRepository $groupRepository)
    {
        $this->calendarRepo = $calendarRepository;
        $this->orgRepo = $organizationRepository;
        $this->groupRepo = $groupRepository;
    }

    public function all($orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }

        return $this->calendarRepo->all($organization);
    }

    public function add($orgId, $data)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }

        $name = new Name($data['name']);
        $start = null;
        if (isset($data['start'])) {
            $start = new DateTime($data['start']);
        }
        $end = null;
        if (isset($data['end'])) {
            $end = new DateTime($data['end']);
        }


        $event = CalendarEvent::register($name, $organization, $start, $end);

        if (isset($data['description'])) {
            $event->setDescription($data['description']);
        }

        if (isset($data['classgroups'])) {
            foreach ($data['classgroups'] as $classgroup) {
                $cg = $this->groupRepo->groupOfId($classgroup['id']);
                $event->addGroup($cg);
            }
        }


        $this->calendarRepo->add($event);

        return $event;
    }

    public function update($id, $data)
    {

    }

    public function remove($id)
    {
        $event = $this->calendarRepo->eventOfId($id);
        $this->calendarRepo->remove($event);
    }

    public function eventsOfGroup($groupId)
    {
        $group = $this->groupRepo->groupOfId($groupId);
        return $this->calendarRepo->eventsOfGroup($group);
    }

    public function eventOfId($id)
    {
        return $this->calendarRepo->eventOfId($id);
    }

    public function eventsBetween($orgId, $start, $end)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        return $this->calendarRepo->eventsBetween($organization, $start, $end);
    }
}