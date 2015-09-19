<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 21:05
 */

namespace Bakgat\Notos\Domain\Model\Location;

use Bakgat\Notos\Domain\Model\Identity\Name;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"location"="Location", "website"="Website"})
 * @ORM\Table(name="locations")
 */
class Location
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @JMS\Groups({"list","detail"})
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"list","detail"})
     */
    private $name;
    /**
     * @ORM\OneToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    private $parent;

    public function __construct(Name $name)
    {
        $this->setName($name);
    }

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param Name name
     * @return void
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
     * @param Location parent
     * @return void
     */
    public function setParent(Location $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Location
     */
    public function parent()
    {
        return $this->parent;
    }
}