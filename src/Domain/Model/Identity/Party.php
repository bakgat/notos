<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 14:00
 */

namespace Bakgat\Notos\Domain\Model\Identity;

use Atrauzzi\LaravelDoctrine\Util\Time;
use Bakgat\Notos\Domain\Model\Kind;
use Bakgat\Notos\Domain\Model\SoftDelete;
use Bakgat\Notos\Domain\Model\Timestamp;
use \DateTime;
use DoctrineExtensions\Query\Mysql\TimestampAdd;
use JMS\Serializer\Annotation as JMS;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"party"="Party", "group"="Group",
 *                      "organization"="Organization",
 *                      "user"="User"})
 * @ORM\Table(name="parties")
 *
 * @JMS\ExclusionPolicy("none")
 */
class Party
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @JMS\Groups({"list","detail", "full"})
     */
    protected $id;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $firstName;
    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"list","detail"})
     */
    protected $lastName;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Kind")
     *
     */
    protected $kind;

    /**
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\Relations\PartyRelation", mappedBy="reference")
     * @JMS\Exclude
     *
     */
    protected $references;
    /**
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\Relations\PartyRelation", mappedBy="context")
     * @JMS\Exclude
     */
    protected $relatedTo;

    public function __construct($name)
    {
        $this->setLastName($name);
    }

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param Name firstName
     * @return void
     */
    public function setFirstName(Name $firstName)
    {
        $this->firstName = $firstName->toString();
    }

    /**
     * @return Name
     */
    public function firstName()
    {
        return Name::fromNative($this->firstName);
    }

    /**
     * @param Name lastName
     * @return void
     */
    public function setLastName(Name $lastName)
    {
        $this->lastName = $lastName->toString();
    }

    /**
     * @return Name
     */
    public function lastName()
    {
        return Name::fromNative($this->lastName);
    }

    /**
     * return string
     * @JMS\VirtualProperty
     */
    public function fullName()
    {
        return ($this->firstName ? $this->firstName . ' ' : '') . $this->lastName;
    }

    /**
     * @param Kind $kind
     */
    public function setKind(Kind $kind)
    {
        $this->kind = $kind;
    }

    /**
     * @return Kind
     */
    public function kind()
    {
        return $this->kind;
    }

    /**
     *
     */
    public function references()
    {
        return $this->references;
    }

    /**
     * @return mixed
     */
    public function relatedTo()
    {
        return $this->relatedTo;
    }
}