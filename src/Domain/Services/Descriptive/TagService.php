<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 22:53
 */

namespace Bakgat\Notos\Domain\Services\Descriptive;


use Bakgat\Notos\Domain\Model\Descriptive\Tag;
use Bakgat\Notos\Domain\Model\Descriptive\TagRepository;
use Bakgat\Notos\Domain\Model\Identity\Name;

class TagService
{
    /** @var TagRepository $tagRepo */
    private $tagRepo;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepo = $tagRepository;
    }

    public function all()
    {
        return $this->tagRepo->all();
    }

    public function add($name)
    {
        $lower = strtolower($name);
        $tag = Tag::register(new Name($lower));
        return $this->tagRepo->add($tag);
    }

    public function update(Tag $tag)
    {
        return $this->tagRepo->update($tag);
    }

    public function tagOfName($name)
    {
        if (!$name instanceof Tag) {
            $name = new Name($name);
        }
        return $this->tagRepo->tagOfName($name);
    }
}