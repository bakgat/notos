<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 12/10/15
 * Time: 22:36
 */

namespace Bakgat\Notos\Disk;


use Bakgat\Notos\Domain\Model\Resource\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController
{
    /** @var ImageRepository $imageRepo */
    private $imageRepo;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepo = $imageRepository;
    }

    public function upload(UploadedFile $uploadedFile)
    {
        $name = $this->generateUniqueName();
        $directory = $this->getDirectory($name);
        $ext = strtolower($uploadedFile->getClientOriginalExtension());

        $image = Image::register($name, $name . '.' . $ext);
        $image->setDirectory($directory);
        $image->setMime($uploadedFile->getClientMimeType());
        $image->setTitle($uploadedFile->getClientOriginalName());
        $this->imageRepo->add($image);

        $fys_img = InterventionImage::make($uploadedFile);

        //TODO: FULL PATH INFO FROM IMAGE
        //ie /blablabla/public/upload/4/e/3/4/4e3409834098938409834.jpg

        $fys_img->save($this->fullPath($img));

        return $image;
    }

    /*
     * PRIVATE FUNCTIONS
     */
    private function generateUniqueName()
    {
        return md5(time() . rand(1, 100));
    }

    private function getDirectory($name)
    {
        if ($this->isValidMd5($name)) {
            $first = str_split($name, 4)[0];
            $splitted = str_split($first);
            $dir_path = implode('/', $splitted);
            $disk = Storage::disk('images');

            if (!$disk->exists($dir_path)) {
                $disk->makeDirectory($dir_path);
            }
            return '/images/' . $dir_path;
        }

        return null;
    }

    private function isValidMd5($md5 = '')
    {
        return preg_match('/^[a-f0-9]{32}$/', $md5);
    }
}