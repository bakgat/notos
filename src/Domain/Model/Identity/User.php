<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/09/15
 * Time: 14:44
 */

namespace Bakgat\Notos\Domain\Model\Identity;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * @ORM\Entity
 * @ORM\Table(name="users", indexes={@ORM\Index(columns={"username", "reset_email"})})
 * @ExclusionPolicy("none")
 */
class User extends Party implements Authenticatable
{

    /** @ORM\Column(type="string", unique=true) */
    private $username;
    /** @ORM\Column(type="string") */
    private $reset_email;
    /**
     * @ORM\Column(type="string", length=60)
     * @Exclude
     */
    private $password;
    /** @ORM\Column(type="string", length=60, nullable=true) */
    private $remember_token;
    /** @ORM\Column(type="boolean") */
    private $locked;
    /** @ORM\Column(type="datetime", nullable=true) */
    private $last_login;
    /**  @ORM\Column(type="datetime", nullable=true) */
    private $last_attempt;


    /**
     * constructor
     */
    public function __construct()
    {

    }

    /**
     * Registers a new user
     *
     * @param Name $firstname
     * @param Name $lastName
     * @param Username $username
     * @param HashedPassword $password
     * @param Email $resetEmail
     * @param Gender $gender
     * @return User
     */
    public static function register(Name $firstname, Name $lastName, Username $username, HashedPassword $password, Email $resetEmail, Gender $gender)
    {
        $user = new User();

        $user->setFirstName($firstname);
        $user->setLastName($lastName);
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setResetEmail($resetEmail);
        $user->setLocked(false);

        $personalInfo = new PersonalInfo($user);
        $personalInfo->setGender($gender);
        $user->setPersonalInfo($personalInfo);

        $user->record(new UserHasRegistered);

        return $user;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        // TODO: Implement getAuthIdentifier() method.
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        // TODO: Implement getAuthPassword() method.
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        // TODO: Implement getRememberToken() method.
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // TODO: Implement setRememberToken() method.
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        // TODO: Implement getRememberTokenName() method.
    }
}