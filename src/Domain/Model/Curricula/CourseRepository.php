<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 22:50
 */

namespace Bakgat\Notos\Domain\Model\Curricula;


use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\Common\Collections\ArrayCollection;

interface CourseRepository
{
    /**
     * Returns all courses
     *
     * @return ArrayCollection
     */
    public function all();

    /**
     * Find a course by it's id.
     *
     * @param $id
     * @return Course
     */
    public function courseOfId($id);
    /**
     * Returns a course by it's name
     *
     * @param Name $name
     * @return Course
     */
    public function courseOfName(Name $name);
}