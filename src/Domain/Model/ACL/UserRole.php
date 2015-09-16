<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 28/08/15
 * Time: 09:04
 */

namespace Bakgat\Notos\Domain\Model\ACL;

use Atrauzzi\LaravelDoctrine\Util\Time;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\User;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_roles")
 */
class UserRole implements \JsonSerializable
{
    use Time;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\User", inversedBy="user_roles")
     * @ORM\JoinColumn(name="user_id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\ACL\Role", inversedBy="user_roles", fetch="EAGER")
     * @ORM\JoinColumn(name="role_id")
     */
    private $role;

    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Organization", inversedBy="user_roles")
     * @ORM\JoinColumn(name="organization_id")
     */
    private $organization;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end;

    public function __construct(User $user, Role $role, Organization $organization, DateTime $start)
    {
        $this->setUser($user);
        $this->setRole($role);
        $this->setOrganization($organization);
        $this->setStart($start);
        $this->setCreatedAt(new DateTime);
        $this->setUpdatedAt(new DateTime);
    }

    public static function  register(User $user, Role $role, Organization $organization)
    {
        return new UserRole($user, $role, $organization, new DateTime);
    }

    /**
     * @return mixed
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @param Role role
     * @return void
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
    }

    /**
     * @return Role
     */
    public function role()
    {
        return $this->role;
    }

    /**
     * @param Organization organization
     * @return void
     */
    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return Organization
     */
    public function organization()
    {
        return $this->organization;
    }

    /**
     * @param DateTime start
     * @return void
     */
    public function setStart(DateTime $start)
    {
        $this->start = $start;
    }

    /**
     * @return DateTime
     */
    public function start()
    {
        return $this->start;
    }

    /**
     * @param DateTime end
     * @return void
     */
    public function setEnd(DateTime $end)
    {
        $this->end = $end;
    }

    /**
     * @return DateTime
     */
    public function end()
    {
        return $this->end;
    }


    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    function jsonSerialize()
    {
        return [
            'user' => $this->user,
            'role' => $this->role,
            'organization' => $this->organization,
            'start' => $this->start,
            'end' => $this->end
        ];
    }
}