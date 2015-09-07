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
}