<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 14:47
 */

namespace Bakgat\Notos\Domain\Model\Identity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="personal_info")
 */
class PersonalInfo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Party",mappedBy="personalInfo")
     */
    private $party;
    /**
     * @ORM\Column(type="string")
     */
    private $gender;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $birthday;


    /**
     * Constructor
     * @param Party $party
     */
    public function __construct(Party $party) {
        $this->setParty($party);
    }


    /**
     * @param Party party
     * @return void
     */
    public function setParty(Party $party)
    {
        $this->party = $party;
    }

    /**
     * @return Party
     */
    public function party()
    {
        return $this->party;
    }

    /**
     * @param Gender gender
     * @return void
     */
    public function setGender(Gender $gender)
    {
        $this->gender = $gender->toString();
    }

    /**
     * @return Gender
     */
    public function gender()
    {
        return Gender::fromNative($this->gender);
    }

    /**
     * @param birthday
     * @return void
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
    }

    /**
     * @return DateTime | null
     */
    public function birthday()
    {
        if (!$this->birthday) {
            return null;
        }
        return date('d-m-Y', strtotime($this->birthday));
    }
}