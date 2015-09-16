<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 27/06/15
 * Time: 10:36
 */

namespace Bakgat\Notos\Domain\Model\Resource;


use Atrauzzi\LaravelDoctrine\Util\Time;
use Bakgat\Notos\Domain\Model\Identity\Name;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"resource"="Resource", "book"="Book", "image"="Image", "album"="Album"})
 * @ORM\Table(name="resources")
 */
class Resource implements \JsonSerializable
{
    use Time;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @ORM\OneToOne(targetEntity="Resource")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    private $parent;

    public function __construct(Name $name)
    {
        $this->setName($name);
        $this->setCreatedAt(new DateTime);
        $this->setUpdatedAt(new DateTime);
    }

    /**
     * @return integer
     */
    public function id()
    {
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
     * @param Resource parent
     * @return void
     */
    public function setParent(Resource $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Resource
     */
    public function parent()
    {
        return $this->parent;
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
            'id' => $this->id,
            'name' => $this->name,
            'kind' => $this->kind
        ];
    }
}