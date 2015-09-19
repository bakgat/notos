<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 21:37
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;

use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="tags")
 */
class Tag
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @JMS\Groups({"list", "detail"})
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     * @JMS\Groups({"list", "detail"})
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Location\Website", inversedBy="tags")
     * @ORM\JoinTable(name="website_tags",
     *      joinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="website_id", referencedColumnName="id")}
     *      ))
     */
    private $websites;


    public function __construct(Name $name)
    {
        $this->setName($name);
    }

    public static function register(Name $name)
    {
        return new Tag($name);
    }

    /**
     * @return mixed
     */
    public function id() {
        return $this->id;
    }

    /**
     * @param Name name
     * @return void
     */
    public function setName(Name $name)
    {
        $this->name = $name;
    }

    /**
     * @return Name
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function websites() {
        return $this->websites;
    }

    public function addWebsite($website)
    {
        $this->websites[] = $website;
    }
}