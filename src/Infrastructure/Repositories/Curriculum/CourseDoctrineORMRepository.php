<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/09/15
 * Time: 22:52
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Curriculum;


use Bakgat\Notos\Domain\Model\Curricula\CourseRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\ORM\EntityManager;

class CourseDoctrineORMRepository implements CourseRepository
{

    /** @var  EntityManager */
    protected $em;
    /** @var  string */
    protected $class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Curricula\Course';
    }

    /**
     * Returns all courses
     *
     * @return mixed
     */
    public function all()
    {
        return $this->em->getRepository($this->class)
            ->findAll();
    }

    /**
     * Find a course by it's id.
     *
     * @param $id
     * @return mixed
     */
    public function courseOfId($id)
    {
        return $this->em->getRepository($this->class)
            ->find($id);
    }

    /**
     * Returns a course by it's name
     *
     * @param Name $name
     * @return mixed
     */
    public function courseOfName(Name $name)
    {
        return $this->em->getRepository($this->class)
            ->findOneBy(['name' => strtolower($name->toString())]);
    }
}