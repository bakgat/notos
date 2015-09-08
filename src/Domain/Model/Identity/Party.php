<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 14:00
 */

namespace Bakgat\Notos\Domain\Model\Identity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"party"="Party","personalInfo" = "PersonalInfo",
 *                      "organization"="Organization","group"="Group",
 *                      "user"="User"})
 * @ORM\Table(name="parties")
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
     * @ORM\OneToOne(targetEntity="PersonalInfo",inversedBy="party", cascade={"persist"})
     * @ORM\JoinColumn(name="personal_info", referencedColumnName="id")
     */
    protected $personalInfo;
    /**
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\Relations\PartyRelation", mappedBy="reference")
     */
    protected $references;
    /**
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\Relations\PartyRelation", mappedBy="context")
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
     * @param PersonalInfo personalInfo
     * @return void
     */
    public function setPersonalInfo(PersonalInfo $personalInfo)
    {
        $this->personalInfo = $personalInfo;
        return $this;
    }

    /**
     * @return PersonalInfo
     */
    public function personalInfo()
    {
        return $this->personalInfo;
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