<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 22:12
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;


interface TagRepository
{
    /**
     * Gets all tags
     * @return mixed
     */
    public function all();

    /**
     * Get all tags used in another table of type
     *
     * @param $type
     * @return mixed
     */
    public function allOfType($type);

    /**
     * Add a tag
     * @param Tag $tag
     * @return mixed
     */
    public function add(Tag $tag);

    /**
     * Updates a tag
     *
     * @param Tag $tag
     * @return mixed
     */
    public function update(Tag $tag);

    /**
     * Removes a tag
     *
     * @param Tag $tag
     * @return mixed
     */
    public function remove(Tag $tag);

    /**
     * Finds a tag by it's name
     *
     * @param TagName $name
     * @return Tag
     */
    public function tagOfName(TagName $name);

    /**
     * Finds a tag by it's name or create one
     *
     * @param TagName $name
     * @return Tag
     */
    public function tagOfNameOrCreate(TagName $name);

}