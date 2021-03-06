<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/09/15
 * Time: 14:20
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Event;


use Bakgat\Notos\Domain\Model\Event\CalendarEvent;
use Bakgat\Notos\Domain\Model\Event\CalendarRepository;
use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class CalendarDoctrineORMRepository implements CalendarRepository
{
    /** @var  EntityManager $em */
    private $em;
    /** @var  string $calendarClass */
    private $calendarClass;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->calendarClass = 'Bakgat\Notos\Domain\Model\Event\CalendarEvent';
    }

    /**
     * Get all events of one Organization
     *
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('c')
            ->from($this->calendarClass, 'c')
            ->where(
                $qb->expr()->eq('c.organization', '?1'),
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        $qb->expr()->isNull('c.end'),
                        $qb->expr()->gte('c.start', '?2')
                    ),
                    $qb->expr()->gt('c.end', '?2')
                ))
            ->setParameter(1, $organization->id())
            ->setParameter(2, new DateTime)
            ->orderBy('c.start');
        return $qb->getQuery()->getResult();
    }

    /**
     * Adds a new event.
     *
     * @param CalendarEvent $event
     * @return void
     */
    public function add(CalendarEvent $event)
    {
        $this->em->persist($event);
        $this->em->flush();
    }

    /**
     * Updates an existing event
     *
     * @param CalendarEvent $event
     * @return void
     */
    public function update(CalendarEvent $event)
    {
        $this->em->persist($event);
        $this->em->flush();
    }

    /**
     * If the event exists, it will be removed
     * @param CalendarEvent $event
     * @return void
     */
    public function remove(CalendarEvent $event)
    {
        $this->em->remove($event);
        $this->em->flush();
    }

    /**
     * Returns all events of a certian group
     *
     * @param Group $group
     * @return ArrayCollection
     */
    public function eventsOfGroup(Group $group)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('c')
            ->from($this->calendarClass, 'c')
            ->join('c.groups', 'g')
            ->where(
                $qb->expr()->eq('g.id', '?1'),
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        $qb->expr()->isNull('c.end'),
                        $qb->expr()->gte('c.start', '?2')
                    ),
                    $qb->expr()->gt('c.end', '?2')
                ))
            ->setParameter(1, $group->id())
            ->setParameter(2, new DateTime);
        return $qb->getQuery()->getResult();
    }

    /**
     * Returns an event with a specific id
     *
     * @param $id
     * @return CalendarEvent
     */
    public function eventOfId($id)
    {
        return $this->em->getRepository($this->calendarClass)
            ->find($id);
    }

    /**
     * Shows all events between a timespan (start, end) of one Organization
     * @param Organization $organization
     * @param $start
     * @param $end
     * @return ArrayCollection
     */
    public function eventsBetween(Organization $organization, $start, $end)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('c')
            ->from($this->calendarClass, 'c')
            ->where(
                $qb->expr()->eq('c.organization', '?1'),
                $qb->expr()->orX(
                    $qb->expr()->andX(
                        $qb->expr()->gte('c.start', '?2'),
                        $qb->expr()->lte('c.start', '?3')
                    ),
                    $qb->expr()->andX(
                        $qb->expr()->gte('c.end', '?2'),
                        $qb->expr()->lte('c.end', '?3')
                    )
                ))
            ->setParameter(1, $organization->id())
            ->setParameter(2, $start)
            ->setParameter(3, $end);
        return $qb->getQuery()->getResult();
    }
}