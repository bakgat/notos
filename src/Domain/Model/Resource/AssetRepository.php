<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 08:48
 */

namespace Bakgat\Notos\Domain\Model\Resource;


interface AssetRepository
{
    public function add(Asset $asset);

    public function update(Asset $asset);

    public function assetsOfType($mime_part);

    public function assetOfGuid($guid);
}