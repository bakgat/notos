<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 13:01
 */

namespace Bakgat\Notos\Domain\Services\Resource;


use Bakgat\Notos\Domain\Model\Identity\Guid;
use Bakgat\Notos\Domain\Model\Identity\Name;
use Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Resource\Asset;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AssetsManager
{
    /** @var AssetRepository $assetRepo */
    private $assetRepo;
    /** @var $disk */
    private $disk;
    /** @var PhpRepository $mimeDetect */
    private $mimeDetect;

    public function __construct(AssetRepository $assetRepository, PhpRepository $mimeDetect)
    {
        $this->assetRepo = $assetRepository;
        $this->disk = Storage::disk(config('assets.uploads.storage'));
        $this->mimeDetect = $mimeDetect;
    }

    /**
     * @param UploadedFile $file
     * @param Organization $organization
     * @return Asset
     */
    public function upload(UploadedFile $file, Organization $organization)
    {
        $mime = $this->mimeDetect->findType($file->getClientOriginalExtension());
        $name = new Name($file->getClientOriginalName());
        $guid = Guid::generate();

        //TODO: pre-test uniqueness (cfr cribbb)
        $asset = Asset::register($name, $guid, $mime, $organization);
        $asset->setTitle($name);

        $this->assetRepo->add($asset);

        $this->disk->put($asset->path(), $file);
        return $asset;
    }


    /**
     * Return the full web path to a file
     */
    public function fileWebpath($path)
    {
        $path = rtrim(config('assets.uploads.webpath'), '/') . '/' .
            ltrim($path, '/');
        return url($path);
    }

    /**
     * Return the mime type
     */
    public function fileMimeType($path)
    {
        return $this->mimeDetect->findType(
            pathinfo($path, PATHINFO_EXTENSION)
        );
    }


    /* ***************************************************
     * PRIVATE FUNCTIONS
     * **************************************************/

    private function getPath(Guid $guid)
    {
        $first = str_split($guid->toString(), 4)[0];
        $splitted = str_split($first);
        $dir_path = implode('/', $splitted);

        $path = '/' . ltrim($dir_path, '/');

        return $path . '/' . $guid->toString();
    }


}