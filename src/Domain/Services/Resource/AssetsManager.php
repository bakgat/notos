<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 13:01
 */

namespace Bakgat\Notos\Domain\Services\Resource;


use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\Guid;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Model\Resource\Asset;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AssetsManager
{
    /** @var AssetRepository $assetRepo */
    private $assetRepo;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;
    /** @var $disk */
    private $disk;
    /** @var PhpRepository $mimeDetect */
    private $mimeDetect;

    public function __construct(AssetRepository $assetRepository, PhpRepository $mimeDetect,
                                OrganizationRepository $organizationRepository)
    {
        $this->assetRepo = $assetRepository;
        $this->orgRepo = $organizationRepository;
        $this->disk = Storage::disk(config('assets.uploads.storage'));
        $this->mimeDetect = $mimeDetect;
    }

    public function all($orgId)
    {
        $organization = $this->checkOrganizationExists($orgId);

        $assets = $this->assetRepo->all($organization);
        return $assets;
    }

    public function assetsOfMimePart($orgId, $mime_part)
    {
        $organization = $this->checkOrganizationExists($orgId);

        $assets = $this->assetRepo->assetsOfMime($organization, $mime_part);
        return $assets;
    }

    public function assetsOfMimePartAndType($orgId, $mime_part, $type)
    {
        $organization = $this->checkOrganizationExists($orgId);

        $assets = $this->assetRepo->assetsOfMimeAndType($organization, $mime_part, $type);
        return $assets;
    }

    public function imagesForWebsites()
    {
        $assets = $this->assetRepo->assetsOfMimeAndType(null, 'image', 'website');
        return $assets;
    }

    /**
     * @param $file
     * @param $orgId
     * @return Asset
     */
    public function upload($file, $orgId, $type = null)
    {
        if ($orgId) {
            $organization = $this->checkOrganizationExists($orgId);
        } else {
            $organization = null;
        }

        $asset = $this->buildAsset($file, $organization, $type);
        $this->assetRepo->add($asset);

        $this->disk->put($asset->path(), $file);

        $this->makeThumbnail($file, $asset);

        return $asset;
    }

    public function import($url, $orgId, $type = null)
    {
        if ($orgId) {
            $organization = $this->checkOrganizationExists($orgId);
        } else {
            $organization = null;
        }
        /** @var \Intervention\Image\Image $image */
        $image = Image::make($url);

        $link_array = explode('/', $url);
        $name = end($link_array);

        $asset = $this->buildAssetFromImage($image, $organization, $name, $type);

        $this->disk->makeDirectory($this->getPathOnly($asset->guid()));
        $this->disk->makeDirectory(config('assets.uploads.thumbs') . $this->getPathOnly($asset->guid()));

        $orPath = $this->getOriginalPath($asset);
        $thPath = $this->getThumbsPath($asset);

        $image->save($orPath);

        $image->widen(500, function ($constraint) {
            $constraint->upsize();
        });

        $image->save($thPath);

        $this->assetRepo->add($asset);
        return $asset;
    }

    /**
     * @param $guid
     * @return Asset
     */
    public function assetOfGuid($guid)
    {
        $guid = Guid::fromNative($guid);
        $asset = $this->assetRepo->assetOfGuid($guid);
        return $asset;
    }


    /* ***************************************************
     * PRIVATE FUNCTIONS
     * **************************************************/

    private function getPathOnly(Guid $guid)
    {
        $first = str_split($guid->toString(), 4)[0];
        $splitted = str_split($first);
        $dir_path = implode('/', $splitted);

        $path = '/' . ltrim($dir_path, '/');

        return $path . '/';
    }

    /**
     * @param $orgId
     * @return mixed
     * @throws OrganizationNotFoundException
     */
    private function checkOrganizationExists($orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }
        return $organization;
    }

    private function getThumbsPath($asset)
    {
        $path = rtrim($this->disk->getDriver()->getAdapter()->getPathPrefix(), '/') .
            config('assets.uploads.thumbs') .
            $asset->path();

        return $path;
    }

    private function getOriginalPath($asset)
    {
        $path = rtrim($this->disk->getDriver()->getAdapter()->getPathPrefix(), '/') .
            $asset->path();

        return $path;
    }

    /**
     * @param $file
     * @param $asset
     */
    private function makeThumbnail($file, Asset $asset)
    {
        $this->disk->makeDirectory(config('assets.uploads.thumbs') . $this->getPathOnly($asset->guid()));
        $thPath = $this->getThumbsPath($asset);

        $thumb = Image::make($file->getRealPath());
        $thumb->widen(500, function ($constraint) {
            $constraint->upsize();
        });

        $thumb->save($thPath);
    }


    /**
     * @param $file
     * @param $organization
     * @return Asset
     */
    private function buildAsset($file, $organization, $type)
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $mime = $this->mimeDetect->findType($ext);
        $name = new Name($file->getClientOriginalName());
        $guid = Guid::generate();

        $asset = Asset::register($name, $guid, $mime, $organization);
        $asset->setTitle($name);
        if (isset($type)) {
            $asset->setType($type);
        }
        return $asset;
    }

    private function buildAssetFromImage(\Intervention\Image\Image $image, $organization, $name, $type)
    {
        $mime = $image->mime();
        $name = new Name($name);
        $guid = Guid::generate();

        $asset = Asset::register($name, $guid, $mime, $organization);
        $asset->setTitle($name);
        if (isset($type)) {
            $asset->setType($type);
        }
        return $asset;
    }


}