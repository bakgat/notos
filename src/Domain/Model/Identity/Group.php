<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/06/15
 * Time: 21:07
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 *
 */
class Group extends Party
{
    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Groups({"detail", "list"})
     */
    private $description;
    /**
     * @ORM\Column(type="string", nullable=true)
     * @JMS\Exclude
     */
    private $avatar;

    public function __construct($name)
    {
        parent::__construct($name);
    }

    public static function register(Name $name)
    {
        return new Group($name);
    }

    /**
     * Setting the name of the organization
     *
     * @param Name $name
     * @return $this
     */
    public function setName(Name $name)
    {
        $this->setLastName($name);
    }

    /**
     * Gets the name of the Group
     *
     * @JMS\VirtualProperty
     */
    public function name()
    {
        return $this->lastName();
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
     * @param  avatar
     * @return void
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function avatar()
    {
        return $this->avatar;
    }
}