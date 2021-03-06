<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 27/06/15
 * Time: 10:36
 */

namespace Bakgat\Notos\Domain\Model\Resource;


use Atrauzzi\LaravelDoctrine\Util\Time;
use Bakgat\Notos\Domain\Model\Identity\Name;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"resource"="Resource", "book"="Book", "asset"="Asset", "image"="Image", "game"="Game"})
 * @ORM\Table(name="resources")
 */
class Resource
{
    use Time;

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
     * @ORM\OneToOne(targetEntity="Resource")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     * @JMS\Exclude
     */
    private $parent;


    public function __construct(Name $name)
    {
        $this->setName($name);
        $this->setCreatedAt(new DateTime);
        $this->setUpdatedAt(new DateTime);
    }

    /**
     * @return integer
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
        $this->name = $name->toString();
    }

    /**
     * @return Name
     */
    public function name()
    {
        return Name::fromNative($this->name);
    }

    /**
     * @param Resource $parent
     */
    public function setParent(Resource $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Resource
     */
    public function parent()
    {
        return $this->parent;
    }


}