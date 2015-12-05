<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/09/15
 * Time: 22:25
 */

namespace Bakgat\Notos\Domain\Model\Curricula;


use Bakgat\Notos\Domain\Model\Identity\Group;
use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="curr_objective_levels")
 */
class ObjectiveControlLevel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @JMS\Groups({"list","detail"})
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Bakgat\Notos\Domain\Model\Identity\Group")
     * @JMS\Groups({"list","detail", "full"})
     */
    private $group;
    /**
     * @ORM\ManyToOne(targetEntity="Objective", inversedBy="levels")
     * @ORM\JoinColumn(onDelete="CASCADE")
     * @JMS\Exclude
     */
    private $objective;
    /**
     * @ORM\Column(type="smallint")
     * @JMS\Groups({"list","detail", "full"})
     */
    private $level;

    public function __construct(Group $group, Objective $objective, $level)
    {
        $this->setGroup($group);
        $this->setObjective($objective);
        $this->setLevel($level);
    }

    public static function register(Group $group, Objective $objective, $level)
    {
        $level = new ObjectiveControlLevel($group, $objective, $level);
        return $level;
    }

    /**
     * @param  id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param Group group
     * @return void
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @return Group
     */
    public function group()
    {
        return $this->group;
    }

    /**
     * @param Objective objective
     * @return void
     */
    public function setObjective(Objective $objective)
    {
        $this->objective = $objective;
    }

    /**
     * @return Objective
     */
    public function objective()
    {
        return $this->objective;
    }

    /**
     * @param level
     * @return void
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function level()
    {
        return $this->level;
    }
}