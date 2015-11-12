<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 12/10/15
 * Time: 21:58
 */

namespace Bakgat\Notos\Image;


use Bakgat\Notos\Domain\Model\Resource\Image;
use Bakgat\Notos\Domain\Model\Resource\ImageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\UploadedFile;


use Intervention\Image\Facades\Image as InterventionImage;

class Upload
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
        $fys_img->save($this->fullPath($img));

        return $image;
    }

    public function setAsTemporary(Image $image) {

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
//$_FILES['image']['tmp_name']
}