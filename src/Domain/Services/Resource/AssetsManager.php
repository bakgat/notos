<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 13:01
 */

namespace Bakgat\Notos\Domain\Services\Resource;


use Bakgat\Notos\__CG__\Bakgat\Notos\Domain\Model\Identity\Organization;
use Bakgat\Notos\Domain\Model\Resource\Asset;
use Bakgat\Notos\Domain\Model\Resource\AssetRepository;
use Dflydev\ApacheMimeTypes\PhpRepository;
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

    public function upload(UploadedFile $file, Organization $organization)
    {
        $mime = $this->mimeDetect->findType($file->getClientOriginalExtension());
        $name = $file->getClientOriginalName();
        $guid = $this->generateGuid();

        $path = $this->getPath($guid);


        //TODO: pre-test uniqueness (cfr cribbb)
        $asset = Asset::register($name, $guid, $mime, $organization);
        $asset->setTitle($name);

        $this->assetRepo->add($asset);

        $this->disk->put($path, $file);
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
    private function generateGuid() {
        return md5(time() . rand(1, 100));
    }

    private function getPath($guid)
    {
        if ($this->isValidMd5($guid)) {
            $first = str_split($guid, 4)[0];
            $splitted = str_split($first);
            $dir_path = implode('/', $splitted);

            $path = '/' . ltrim($dir_path, '/');

            return $path . '/' . $guid;
        }

        return null;
    }

    private function isValidMd5($md5 = '')
    {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }
}