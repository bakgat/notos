<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 22:50
 */

namespace Bakgat\Notos\Domain\Model\Curricula;


use Bakgat\Notos\Domain\Model\Identity\Name;

interface CourseRepository
{
    /**
     * Returns all courses
     *
     * @return mixed
     */
    public function all();

    /**
     * Find a course by it's id.
     *
     * @param $id
     * @return mixed
     */
    public function courseOfId($id);
    /**
     * Returns a course by it's name
     *
     * @param Name $name
     * @return mixed
     */
    public function courseOfName(Name $name);
}