<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 12/10/15
 * Time: 22:06
 */

namespace Bakgat\Notos\Domain\Model\Resource;


interface ImageRepository
{
    public function add(Image $image);

    public function update(Image $image);



    /**
     * Returns the metadata of the image with a certain name
     *
     * @param $name
     * @return mixed
     */
    public function imageOfName($name);

}