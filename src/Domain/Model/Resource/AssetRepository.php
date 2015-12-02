<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 08:48
 */

namespace Bakgat\Notos\Domain\Model\Resource;


use Bakgat\Notos\Domain\Model\Identity\Organization;
use Doctrine\Common\Collections\ArrayCollection;

interface AssetRepository
{
    /**
     * @param Organization $organization
     * @return ArrayCollection
     */
    public function all(Organization $organization);

    /**
     * @param Asset $asset
     * @return mixed
     */
    public function add(Asset $asset);

    /**
     * @param Asset $asset
     * @return mixed
     */
    public function update(Asset $asset);

    /**
     * @param $organization
     * @param $mime_part
     * @return ArrayCollection
     */
    public function assetsOfMime($organization, $mime_part);

    /**
     * @param $organization
     * @param $mime_part
     * @param $type
     * @return ArrayCollection
     */
    public function assetsOfMimeAndType($organization, $mime_part, $type);

    /**
     * @param $guid
     * @return Asset
     */
    public function assetOfGuid($guid);

}