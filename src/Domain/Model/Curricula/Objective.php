<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 21:06
 */

namespace Bakgat\Notos\Domain\Model\Curricula;

use Bakgat\Notos\Domain\Model\Identity\Group;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Location\Website;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="curr_objectives", indexes={@ORM\Index(columns={"code"})})
 */
class Objective
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Groups({"list","detail"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Structure", inversedBy="objectives")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @JMS\Exclude
     */
    private $structure;

    /**
     * @ORM\Column(type="text", length=65535)
     * @JMS\Groups({"list", "detail"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     * @JMS\Groups({"list","detail"})
     */
    private $code;


    /**
     * @ORM\ManyToMany(targetEntity="Bakgat\Notos\Domain\Model\Location\Website", mappedBy="objectives")
     * @JMS\Exclude
     **/
    private $websites;

    /**
     * @ORM\ManyToOne(targetEntity="Objective", inversedBy="children")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @JMS\Groups({"detail", "full"})
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Objective", mappedBy="parent")
     * @JMS\Exclude
     */
    private $children;

    /**
     * @ORM\OneToMany(targetEntity="ObjectiveControlLevel", mappedBy="objective")
     * @JMS\Groups({"detail", "website_detail", "full"})
     */
    private $levels;

    public function __construct(Name $name, $code, Structure $structure)
    {
        $this->setName($name);
        $this->setCode($code);
        $this->setStructure($structure);
        $this->children = new ArrayCollection;
        $this->websites = new ArrayCollection;
    }

    public static function register(Name $name, $code, Structure $structure)
    {
        $objective = new Objective($name, $code, $structure);
        return $objective;
    }

    /**
     * Returns the read-only id.
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Structure structure
     * @return void
     */
    public function setStructure(Structure $structure)
    {
        $this->structure = $structure;
    }

    /**
     * @return Structure
     */
    public function structure()
    {
        return $this->structure;
    }

    /**
     * @param  name
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
     * @param code
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return ObjectiveCode
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * Returns all websites that are linked to this objective.
     *
     * @return ArrayCollection
     */
    public function getWebsites()
    {
        return $this->websites;
    }

    /**
     * Adds a website to this objective.
     *
     * @param Website $website
     */
    public function addWebsite(Website $website)
    {
        $this->websites[] = $website;
    }

    /**
     * Removes a website from this objective.
     *
     * @param Website $website
     */
    public function removeWebsite(Website $website)
    {
        $this->websites->removeElement($website);
    }

    /**
     * @param Objective parent
     * @return void
     */
    public function setParent(Objective $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Objective
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * @param  children
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
     * @param Objective $child
     */
    public function addChild(Objective $child)
    {
        $this->children[] = $child;
    }

    /**
     * @param Objective $child
     */
    public function removeChild(Objective $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * @param ArrayCollection levels
     * @return void
     */
    public function setLevels(ArrayCollection $levels)
    {
        $this->levels = $levels;
    }

    /**
     * @return ArrayCollection
     */
    public function levels()
    {
        return $this->levels;
    }

    /**
     * @param Group $group
     * @param $level
     */
    public function addLevelForGroup(Group $group, $level)
    {
        $this->levels[] = new ObjectiveControlLevel($group, $this, $level);
    }
}