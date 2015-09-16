<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 21:02
 */

namespace Bakgat\Notos\Domain\Model\Curricula;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="curricula", indexes={@ORM\Index(columns={"year_published", "code"})})
 */
class Curriculum
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Course")
     * @ORM\JoinColumn(name="course_id", onDelete="CASCADE")
     */
    private $course;

    /**
     * @ORM\Column(type="integer")
     */
    private $year_published;

    /**
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="Structure", mappedBy="curriculum")
     */
    private $structures;

    public function __construct(Course $course, $year_published)
    {
        $this->setCourse($course);
        $this->setYearPublished($year_published);

        $this->structures = new ArrayCollection();
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
     * @param Course course
     * @return void
     */
    public function setCourse(Course $course)
    {
        $this->course = $course;
    }

    /**
     * @return Course
     */
    public function course()
    {
        return $this->course;
    }

    /**
     * @param  yearPublished
     * @return void
     */
    public function setYearPublished($yearPublished)
    {
        $this->year_published = $yearPublished;
    }

    /**
     * @return
     */
    public function yearPublished()
    {
        return $this->year_published;
    }

    /**
     * @param  code
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * Returns all structures for this curriculum
     *
     * @return ArrayCollection
     */
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * Adds a new Structure
     *
     * @param Structure $structure
     */
    public function addStructure(Structure $structure)
    {
        $this->structures[] = $structure;
    }

    /**
     * Removes a Structure
     *
     * @param Structure $structure
     */
    public function removeStructure(Structure $structure)
    {
        $this->structures->removeElement($structure);
    }
}