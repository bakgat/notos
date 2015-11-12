<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 15/10/15
 * Time: 13:48
 */

namespace Bakgat\Notos\Domain\Model\Descriptive;


interface TagNameSpecification
{
    /**
     * Check to see if the specification is satisfied
     *
     * @param TagName $tag
     * @return bool
     */
    public function isSatisfiedBy(TagName $tag);
}