<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 30/09/15
 * Time: 13:48
 */

namespace Bakgat\Notos\Domain\Model\Event;

use Bakgat\Notos\Domain\Model\Identity\Name;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"CE"="CalendarEvent"})
 * @ORM\Table(name="events", indexes={@ORM\Index(columns={"name"})})
 */
abstract class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @JMS\Groups({"list","detail","full"})
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"list","detail","full"})
     */
    private $name;
    /**
     * @ORM\Column(type="datetime")
     * @JMS\Groups({"list","detail","full"})
     */
    private $start;

    public function __construct(Name $name, DateTime $start = null)
    {
        if (!$start) {
            $start = new DateTime;
        }
        $this->setName($name);
        $this->setStart($start);
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param Name $name
     */
    public function setName(Name $name)
    {
        $this->name = $name;
    }

    /**
     * @return Name
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param DateTime $start
     */
    public function setStart(DateTime $start)
    {
        $this->start = $start;
    }

    /**
     * @return DateTime
     */
    public function start()
    {
        return $this->start;
    }
}