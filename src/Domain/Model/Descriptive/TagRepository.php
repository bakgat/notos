<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 22:12
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;


use Bakgat\Notos\Domain\Model\Identity\Name;

interface TagRepository
{
    /**
     * Gets all tags
     * @return mixed
     */
    public function all();

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
     * @param Name $name
     * @return mixed
     */
    public function tagOfName(Name $name);
}