<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 18/06/15
 * Time: 21:07
 */

namespace Bakgat\Notos\Domain\Model\Identity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="string", nullable=true)
     */
    private $domain_name;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    public function __construct($name)
    {
        parent::__construct($name);
    }

    public static function register(Name $name)
    {
        return new Organization($name);
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
     */
    public function setName(Name $name) {
        $this->setLastName($name);
        return $this;
    }

    /**
     * Gets the name of the organization
     *
     * @return Name
     */
    public function name() {
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


}