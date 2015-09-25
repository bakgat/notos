<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 21:03
 */

namespace Bakgat\Notos\Domain\Model\Curricula;

use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Support\Arr;
use SebastianBergmann\Comparator\Struct;

/**
 * @ORM\Entity
 * @ORM\Table(name="curr_structures", indexes={@ORM\Index(columns={"name"})})
 */
class Structure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Curriculum", inversedBy="structures")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $curriculum;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Structure", inversedBy="children")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Structure", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="Objective", mappedBy="structure")
     */
    private $objectives;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    public function __construct()
    {
        $this->children = new ArrayCollection;
        $this->objectives = new ArrayCollection;
    }

    public static function register(Curriculum $curriculum, Name $name, $type)
    {
        $structure = new Structure();
        $structure->setCurriculum($curriculum);
        $structure->setName($name);
        $structure->setType($type);
        return $structure;
    }

    /**
     * @return mixed
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
     * @param Curriculum curriculum
     * @return void
     */
    public function setCurriculum(Curriculum $curriculum)
    {
        $this->curriculum = $curriculum;
    }

    /**
     * @return Curriculum
     */
    public function curriculum()
    {
        return $this->curriculum;
    }

    /**
     * @param Structure parent
     * @return void
     */
    public function setParent(Structure $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Structure
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @param ArrayCollection children
     * @return void
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return ArrayCollection
     */
    public function children()
    {
        return $this->children;
    }

    /**
     * @param Structure $structure
     */
    public function addChild(Structure $structure)
    {
        $this->children[] = $structure;
    }

    /**
     * @param ArrayCollection objectives
     * @return void
     */
    public function setObjectives(ArrayCollection $objectives)
    {
        $this->objectives = $objectives;
    }

    /**
     * @return ArrayCollection
     */
    public function objectives()
    {
        return $this->objectives;
    }

    /**
     * @param Objective $objective
     */
    public function addObjective(Objective $objective)
    {
        $this->objectives[] = $objective;
        $objective->setStructure($this);
    }

    /**
     * @param  type
     * @return void
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return
     */
    public function type()
    {
        return $this->type;
    }
}