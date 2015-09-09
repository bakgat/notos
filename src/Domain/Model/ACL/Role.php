<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/07/15
 * Time: 11:50
 */

namespace Bakgat\Notos\Domain\Model\ACL;

use Assert\Assertion;
use Atrauzzi\LaravelDoctrine\Util\Time;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Arr;

/**
 * @ORM\Entity
 * @ORM\Table(name="roles", indexes={@ORM\Index(columns={"name", "slug"})})
 */
class Role
{
    use Time, HasPermission;

    /**
     * @ORM\id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Permission", cascade={"persist"})
     * @ORM\JoinTable(name="role_permissions")
     */
    private $permissions;

    /*
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Identity\User", mappedBy="user_roles")
     */
    //private $users;
    /**
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\ACL\UserRole", mappedBy="role")
     */
    private $user_roles;

    /**
     * @param $name
     */
    public function __construct($name)
    {
        Assertion::string($name);

        $this->setName($name);
        $this->setSlug(strtolower($name));

        $this->setCreatedAt(new DateTime);
        $this->setUpdatedAt(new DateTime);


        $this->permissions = new ArrayCollection;
    }

    public static function register($name)
    {
        return new Role($name);
    }

    /**
     * @return
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param  name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param  slug
     * @return void
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return
     */
    public function slug()
    {
        return $this->slug;
    }

    /**
     * @param  description
     * @return void
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return
     */
    public function description()
    {
        return $this->description;
    }

    /**
     * Returns all original permissions
     *
     * @return ArrayCollection
     */
    public function permissions() {
        return $this->permissions;
    }

    /**
     * List all calculated permissions
     *
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->getPermissionsInherited();
    }

    /**
     * Checks if the role has the given permission.
     *
     * @param string $permission
     * @param string $operator
     * @param array $mergePermissions
     * @return bool
     */
    public function can($permission, $operator = null, $mergePermissions = [])
    {
        $operator = is_null($operator) ? $this->parseOperator($permission) : $operator;
        $permission = $this->hasDelimiterToArray($permission);
        $permissions = $this->getPermissions() + $mergePermissions;
        // make permissions to dot notation.
        // create.user, delete.admin etc.
        $permissions = $this->toDotPermissions($permissions);
        // validate permissions array
        if (is_array($permission)) {
            if (!in_array($operator, ['and', 'or'])) {
                $e = 'Invalid operator, available operators are "and", "or".';
                throw new \InvalidArgumentException($e);
            }
            $call = 'canWith' . ucwords($operator);
            return $this->$call($permission, $permissions);
        }
        // validate single permission
        return isset($permissions[$permission]) && $permissions[$permission] == true;
    }

    /**
     * @param $permission
     * @param $permissions
     * @return bool
     */
    protected function canWithAnd($permission, $permissions)
    {
        foreach ($permission as $check) {
            if (!in_array($check, $permissions) || !isset($permissions[$check]) || $permissions[$check] != true) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $permission
     * @param $permissions
     * @return bool
     */
    protected function canWithOr($permission, $permissions)
    {
        foreach ($permission as $check) {
            if (in_array($check, $permissions) && isset($permissions[$check]) && $permissions[$check] == true) {
                return true;
            }
        }
        return false;
    }


}