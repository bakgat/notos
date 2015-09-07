<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/06/15
 * Time: 11:04
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Party;
use Bakgat\Notos\Domain\Model\Identity\PersonalInfo;
use DateTime;
use DateTimeZone;

class PersonalInfoTest extends \PHPUnit_Framework_TestCase
{

    /** @var Name $firstName */
    private $firstName;
    /** @var Name $lastName */
    private $lastName;
    /** @var Gender $gender */
    private $gender;
    /** @var DateTime $birthDay */
    private $birthDay;


    public function setUp()
    {
        $this->firstName = new Name('Karl');
        $this->lastName = new Name('Van Iseghem');
        $this->gender = new Gender('Male');
        $this->birthDay = new DateTime('1979-11-30', new DateTimeZone('Europe/Brussels'));


    }

    /**
     * @test
     * @group personalInfo
     */
    public function should_create_personalInfo()
    {
        $party = new Party($this->lastName);
        $party->setFirstName($this->firstName);

        $pi = new PersonalInfo($party);
        $pi->setGender($this->gender);
        $pi->setBirthday($this->birthDay);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\PersonalInfo', $pi);
        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\Party', $pi->party());
    }


}
