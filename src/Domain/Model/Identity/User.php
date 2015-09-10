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
use Illuminate\Contracts\Auth\CanResetPassword;
use \DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="users", indexes={@ORM\Index(columns={"username", "reset_email"})})
 */
class User extends Party implements Authenticatable, CanResetPassword
{

    /** @ORM\Column(type="string", unique=true) */
    private $username;
    /** @ORM\Column(type="string") */
    private $reset_email;
    /**
     * @ORM\Column(type="string", length=60)
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
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\ACL\UserRole", mappedBy="user")
     */
    private $user_roles;

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


        return $user;
    }

    /**
     * @param Username username
     * @return void
     */
    public function setUsername(Username $username)
    {
        $this->username = $username->toString();
    }

    /**
     * @return Username
     */
    public function username()
    {
        return Username ::fromNative($this->username);
    }

    /**
     * @param Email $reset_email
     * @return void
     */
    public function setResetEmail(Email $reset_email)
    {
        $this->reset_email = $reset_email->toString();
    }

    /**
     * Sets the user's hashed password
     *
     * @param HashedPassword $password
     */
    public function setPassword(HashedPassword $password)
    {
        $this->password = $password->toString();
    }

    /**
     * @param locked
     * @return void
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
    }

    /**
     * @return boolean
     */
    public function locked()
    {
        return $this->locked;
    }

    /**
     * @param DateTime last_login
     * @return void
     */
    public function setLastLogin(DateTime $last_login)
    {
        $this->last_login = $last_login;
    }

    /**
     * @return DateTime
     */
    public function lastLogin()
    {
        return $this->last_login;
    }

    /**
     * @param DateTime last_attempt
     * @return void
     */
    public function setLastAttempt(DateTime $last_attempt)
    {
        $this->last_attempt = $last_attempt;
    }

    /**
     * @return DateTime
     */
    public function lastAttempt()
    {
        return $this->last_attempt;
    }

    /**
     * Updates the user's username
     * @param Username $username
     */
    public function updateUsername(Username $username)
    {
        $this->setUsername($username);
    }

    /**
     * Locks a user
     */
    public function lock()
    {
        $this->setLocked(true);

    }

    /**
     * Unlocks a user
     */
    public function unlock()
    {
        $this->setLocked(false);
    }

    /**
     * Get all the user roles
     * @return mixed
     */
    public function userRoles() {
        return $this->user_roles;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberToken()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->reset_email;
    }
}