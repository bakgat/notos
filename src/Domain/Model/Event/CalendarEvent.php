<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/09/15
 * Time: 13:53
 */

namespace Bakgat\Notos\Domain\Model\Event;

use Bakgat\Notos\Domain\Model\Identity\Organization;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="calendar_events")
 */
class CalendarEvent extends Event
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $end;

    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Identity\Group")
     */
    private $groups;

    /**
     * @ORM\Column(type="string")
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private $allDay;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization")
     */
    private $organization;

    public function __construct(Name $name, Organization $organization, DateTime $start = null, DateTime $end = null, $allDay = false)
    {
        parent::__construct($name, $start);
        if (!$end) {
            $this->setEnd($end);
        }
        $this->setAllDay($allDay);
    }

    public static function register(Name $name, Organization $organization, DateTime $start = null, DateTime $end = null)
    {
        return new CalendarEvent($name, $organization, $start, $end);
    }

    /**
     * @param DateTime end
     * @return void
     */
    public function setEnd(DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return DateTime
     */
    public function end()
    {
        return $this->end;
    }

    /**
     * @param ArrayCollection groups
     * @return void
     */
    public function setGroups(ArrayCollection $groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return ArrayCollection
     */
    public function groups()
    {
        return $this->groups;
    }

    /**
     * @param string description
     * @return void
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * @param Organization organization
     * @return void
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return Organization
     */
    public function organization()
    {
        return $this->organization;
    }

    /**
     * @param allDay
     * @return void
     */
    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;
    }

    /**
     * @return boolean
     */
    public function allDay()
    {
        return $this->allDay;
    }
}