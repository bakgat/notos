<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 12/10/15
 * Time: 20:54
 */

namespace Bakgat\Notos\Image\Templates;


use Intervention\Image\Filters\FilterInterface;

class HD implements FilterInterface
{

    /**
     * Applies filter to given image
     *
     * @param  \Intervention\Image\Image $image
     * @return \Intervention\Image\Image
     */
    public function applyFilter(\Intervention\Image\Image $image)
    {
        return $image->fit(1920, 1080);
    }
}