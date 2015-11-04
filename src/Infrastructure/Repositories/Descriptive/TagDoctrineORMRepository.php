<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 22:14
 */

namespace Bakgat\Notos\Infrastructure\Repositories\Descriptive;


use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Descriptive\TagName;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Cache;

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
     *
     * @param TagName $name
     * @return Tag
     */
    public function tagOfName(TagName $name)
    {
        $tag = $this->em->getRepository($this->class)
            ->findOneBy(['name' => strtolower($name->toString())]);

        return $tag;
    }

    /**
     * Finds a tag by it's name or create one
     *
     * @param TagName $name
     * @return Tag
     */
    public function tagOfNameOrCreate(TagName $name)
    {
        $tag = $this->tagOfName($name);
        if ($tag === null) {
            $tag = Tag::register($name);
            $this->add($tag);
        }
        return $tag;
    }

}