<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 14:03
 */

namespace Bakgat\Notos\Tests\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Identity\Email;
use Bakgat\Notos\Domain\Model\Identity\Gender;
use Bakgat\Notos\Domain\Model\Identity\HashedPassword;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\User;
use Bakgat\Notos\Domain\Model\Identity\Username;
use DateTime;
use DateTimeZone;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /** @var Name $firstName */
    private $firstName;
    /** @var Name $lastName */
    private $lastName;
    /** @var Gender $gender */
    private $gender;
    /** @var DateTime $birthDay */
    private $birthDay;
    /** @var  Username $username */
    private $username;
    /** @var  Hashedpassword $password */
    private $password;
    /** @var  Email $email */
    private $email;

    public function setUp()
    {
        $this->firstName = new Name('Karl');
        $this->lastName = new Name('Van Iseghem');
        $this->gender = new Gender('Male');
        $this->birthDay = new DateTime('1979-11-30', new DateTimeZone('Europe/Brussels'));
        $this->username = new Username('karl.vaniseghem@klimtoren.be');
        $this->password = new HashedPassword(md5('mypassword'));
        $this->email = new Email('karl.vaniseghem@klimtoren.be');

    }

    /**
     * @test
     * @group user
     */
    public function should_register_new_user()
    {
        $user = User::register($this->firstName,
            $this->lastName,
            $this->username,
            $this->password,
            $this->email,
            $this->gender);

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
    }
}
