<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 7/07/15
 * Time: 15:25
 */

namespace Bakgat\Notos\Domain\Model\ACL;

use Assert\Assertion;
use Atrauzzi\LaravelDoctrine\Util\Time;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="permissions", indexes={@ORM\Index(columns={"name"})},
 *              uniqueConstraints={@ORM\UniqueConstraint(name="name_slug_unique", columns={"name", "slug"})})
 */
class Permission
{
    use Time;

    /**
     * @ORM\id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Permission")
     */
    private $inherit;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="text", length=65535, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="permissions")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Identity\User", mappedBy="permissions")
     */
    private $users;

    public function __construct($name, $slug)
    {
        Assertion::string($name);
        Assertion::isArray($slug);

        $this->setName($name);
        $this->setSlug($slug);

        $this->setCreatedAt(new DateTime);
        $this->setUpdatedAt(new DateTime);

        $this->roles = new ArrayCollection;
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
    public function setSlug($value)
    {
        // if nothing being set, clear slug
        if (empty($value)) {
            $this->attributes['slug'] = '[]';
            return;
        }
        $value = is_array($value) ? $value : [$value => true];
        // if attribute is being updated.
        if (isset($this->slug)) {
            $value = $value + json_decode($this->slug, true);
            // sort by key
            ksort($value);
        }
        // remove null values.
        $value = array_filter($value, 'is_bool');
        // store as json.
        $this->slug = json_encode($value);
    }

    /**
     * @return array
     */
    public function slug()
    {
        return json_decode($this->slug, true);
    }

    /**
     * @param Permission inherit
     * @return void
     */
    public function setInherit(Permission $inherit)
    {
        $this->inherit = $inherit;
    }

    /**
     * @return Permission
     */
    public function inherit()
    {
        return $this->inherit;
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
     * Returns all the user_roles that has this permission
     *
     * @return ArrayCollection
     */
    public function roles()
    {
        return $this->roles;
    }

    /**
     * Return all the users that has this permission
     *
     * @return mixed
     */
    public function users() {
        return $this->users;
    }

}