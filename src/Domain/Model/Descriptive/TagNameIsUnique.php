<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 13:49
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;


class TagNameIsUnique implements TagNameSpecification
{
    /** @var TagRepository $tagRepo */
    private $tagRepo;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepo = $tagRepository;
    }

    /**
     * Check to see if the specification is satisfied
     *
     * @param TagName $tag
     * @return bool
     */
    public function isSatisfiedBy(TagName $tag)
    {
        if(!$this->tagRepo->tagOfName($tag)) {
            return true;
        }
        return false;
    }
}