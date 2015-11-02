<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 21:01
 */

namespace Bakgat\Notos\Domain\Model\Curricula;

use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="courses", indexes={@ORM\Index(columns={"name"})})
 */
class Course
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var Name $name
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Course", inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="parent")
     */
    private $children;

    public function __construct(Name $name)
    {
        $this->setName($name);
    }

    public static function register(Name $name)
    {
        return new Course($name);
    }

    public function id()
    {
        return $this->id;
    }

    /**
     * @param Name $name
     */
    public function setName(Name $name)
    {
        $this->name = $name->toString();
    }

    /**
     * @return Name
     */
    public function name()
    {
        return Name::fromNative($this->name);
    }

    /**
     * @param Course parent
     * @return void
     */
    public function setParent(Course $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Course
     */
    public function parent()
    {
        return $this->parent;
    }

    /**
     * Gets the children of this Course
     * @return mixed
     */
    public function children()
    {
        return $this->children;
    }

    /**
     * Add a new child to this Course.
     * @param Course $course
     */
    public function addChild(Course $course)
    {
        $this->children[] = $course;
    }

    public function removeChild(Course $course)
    {
        $this->children->removeElement($course);
    }
}