<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 14:00
 */

namespace Bakgat\Notos\Domain\Model\Identity;

use Bakgat\Notos\Domain\Model\Kind;
use JMS\Serializer\Annotation as JMS;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"party"="Party",
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
     */
    protected $id;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    protected $firstName;
    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Kind")
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
        $this->firstName = $firstName;
    }

    /**
     * @return Name
     */
    public function firstName()
    {
        return $this->firstName;
    }

    /**
     * @param Name lastName
     * @return void
     */
    public function setLastName(Name $lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return Name
     */
    public function lastName()
    {
        return $this->lastName;
    }

    /**
     * @param Kind kind
     * @return void
     */
    public function setKind(Kind $kind)
    {
        $this->kind = $kind;
        return $this;
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