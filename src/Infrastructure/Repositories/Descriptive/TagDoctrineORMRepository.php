<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 22:14
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Descriptive;


use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\ORM\EntityManager;

class TagDoctrineORMRepository implements TagRepository
{
    /** @var EntityManager $em */
    private $em;
    /** @var string $class */
    private $class;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->class = 'Bakgat\Notos\Domain\Model\Descriptive\Tag';
    }

    /**
     * Gets all tags
     * @return mixed
     */
    public function all()
    {
        return $this->em->getRepository($this->class)
            ->findAll();
    }

    /**
     * Add a tag
     * @param Tag $tag
     * @return mixed
     */
    public function add(Tag $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }

    /**
     * Updates a tag
     *
     * @param Tag $tag
     * @return mixed
     */
    public function update(Tag $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }

    /**
     * Removes a tag
     *
     * @param Tag $tag
     * @return mixed
     */
    public function remove(Tag $tag)
    {
        $this->em->remove($tag);
        $this->em->flush();
    }

    /**
     * Finds a tag by it's name
     * @param Name $name
     * @return mixed
     */
    public function tagOfName(Name $name)
    {
        return $this->em->getRepository($this->class)
            ->findOneBy(['name' => strtolower($name->toString())]);
    }
}