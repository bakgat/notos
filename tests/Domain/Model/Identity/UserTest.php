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
use Orchestra\Testbench\TestCase;

class UserTest extends TestCase
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
        $user = $this->registerUser();

        $this->assertInstanceOf('Bakgat\Notos\Domain\Model\Identity\User', $user);
    }

    /**
     * @test
     * @group user-model
     */
    public function should_update_name()
    {
        $user = $this->registerUser();

        $user->updateUsername(new Username('ulrike.drieskens@gmail.com'));

        $this->assertEquals('ulrike.drieskens@gmail.com', $user->username()->toString());

    }

    /**
     * @test
     * @group user-model
     */
    public function should_lock_user()
    {
        $user = $this->registerUser();

        $user->lock();

        $this->assertTrue($user->locked());
    }

    /**
     * @test
     * @group user-model
     */
    public function should_unlock_user()
    {
        $user = $this->registerUser();

        $user->setLocked(true);
        $user->unlock();

        $this->assertFalse($user->locked());
    }

    /**
     * @return User
     */
    private function registerUser()
    {
        $user = User::register($this->firstName,
            $this->lastName,
            $this->username,
            $this->password,
            $this->email,
            $this->gender);
        return $user;
    }
}
