<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/09/15
 * Time: 14:31
 */

namespace Bakgat\Notos\Domain\Services\Event;


use Bakgat\Notos\Domain\Model\Event\CalendarRepository;
use Bakgat\Notos\Domain\Model\Identity\GroupRepository;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;

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
        return $this->calendarRepo->all($organization);
    }

    public function add($orgId, $data)
    {

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