<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 17/06/15
 * Time: 23:11
 */

namespace Bakgat\Notos\Domain\Model;

use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="kinds")
 */
class Kind
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $name;
    /**
     * @ORM\OneToOne(targetEntity="Kind")
     * @ORM\JoinColumn(name="parent", referencedColumnName="id")
     */
    private $parent;

    public function __construct($name, $parent = null)
    {
        $this->setName($name);
        if ($parent) {
            $this->setParent($parent);
        }
    }

    /**
     * @return integer
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param string name
     * @return void
     */
    public function setName($name)
    {
        $this->name = strtoupper($name);
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @param Kind parent
     * @return void
     */
    public function setParent(Kind $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Kind
     */
    public function parent()
    {
        return $this->parent;
    }


}