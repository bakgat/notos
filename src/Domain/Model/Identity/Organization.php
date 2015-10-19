<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/06/15
 * Time: 21:07
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use Bakgat\Notos\Domain\Model\Resource\Asset;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="organizations",
 *  uniqueConstraints={
 *          @ORM\UniqueConstraint(name="unique_domainname",columns={"domain_name"})
 *      }))
 *
 */
class Organization extends Party
{

    /**
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    private $domain_name;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\ACL\UserRole", mappedBy="organization")
     * @JMS\Exclude
     */
    private $user_roles;

    /**
     * @ORM\OneToMany(targetEntity="Bakgat\Notos\Domain\Model\Resource\Asset", mappedBy="organization")
     * @var ArrayCollection
     */
    private $assets;


    public function __construct($name)
    {
        parent::__construct($name);
    }

    public static function register(Name $name, DomainName $domainName)
    {
        $org = new Organization($name);
        $org->setDomainName($domainName);
        return $org;
    }


    /**
     * @param DomainName $domain_name
     * @return void
     */
    public function setDomainName(DomainName $domain_name)
    {
        $this->domain_name = $domain_name->toString();
        return $this;
    }

    /**
     * @return string
     */
    public function domainName()
    {
        return DomainName::fromNative($this->domain_name);
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
        return $this;
    }

    /**
     * Gets the name of the organization
     *
     * @return Name
     * @JMS\VirtualProperty
     */
    public function name()
    {
        return $this->lastName();
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

    /**
     * @return ArrayCollection
     */
    public function assets()
    {
        return $this->assets;
    }

    /**
     * @param Asset $asset
     */
    public function addAsset(Asset $asset)
    {
        $this->assets[] = $asset;
    }

    /**
     * @param Asset $asset
     */
    public function removeAsset(Asset $asset)
    {
        $this->assets->removeElement($asset);
    }
}