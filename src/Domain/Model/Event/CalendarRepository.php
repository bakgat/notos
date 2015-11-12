<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/09/15
 * Time: 14:02
 */

namespace Bakgat\Notos\Domain\Model\Event;


use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Doctrine\Common\Collections\ArrayCollection;

interface CalendarRepository
{
    /**
     * Get all events of one Organization
     *
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization);

    /**
     * Adds a new event.
     *
     * @param CalendarEvent $event
     * @return void
     */
    public function add(CalendarEvent $event);

    /**
     * Updates an existing event
     *
     * @param CalendarEvent $event
     * @return void
     */
    public function update(CalendarEvent $event);

    /**
     * If the event exists, it will be removed
     * @param CalendarEvent $event
     * @return void
     */
    public function remove(CalendarEvent $event);

    /**
     * Returns all events of a certian group
     *
     * @param Group $group
     * @return ArrayCollection
     */
    public function eventsOfGroup(Group $group);

    /**
     * Returns an event with a specific id
     *
     * @param $id
     * @return CalendarEvent
     */
    public function eventOfId($id);

    /**
     * Shows all events between a timespan (start, end) of one Organization
     * @param Organization $organization
     * @param $start
     * @param $end
     * @return ArrayCollection
     */
    public function eventsBetween(Organization $organization, $start, $end);


}