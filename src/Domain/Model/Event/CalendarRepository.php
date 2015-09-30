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

interface CalendarRepository
{
    /**
     * Get all events of one Organization
     *
     * @param Organization $organization
     * @return mixed
     */
    public function all(Organization $organization);

    /**
     * Adds a new event.
     *
     * @param CalendarEvent $event
     * @return mixed
     */
    public function add(CalendarEvent $event);

    /**
     * Updates an existing event
     *
     * @param CalendarEvent $event
     * @return mixed
     */
    public function update(CalendarEvent $event);

    /**
     * If the event exists, it will be removed
     * @param CalendarEvent $event
     * @return mixed
     */
    public function remove(CalendarEvent $event);

    /**
     * Returns all events of a certian group
     *
     * @param Group $group
     * @return mixed
     */
    public function eventsOfGroup(Group $group);

    /**
     * Returns an event with a specific id
     *
     * @param $id
     * @return mixed
     */
    public function eventOfId($id);

    /**
     * Shows all events between a timespan (start, end) of one Organization
     * @param Organization $organization
     * @param $start
     * @param $end
     * @return mixed
     */
    public function eventsBetween(Organization $organization, $start, $end);


}