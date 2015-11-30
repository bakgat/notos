<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 16:53
 */

namespace Bakgat\Notos\Http\Controllers\Resource;


use Bakgat\Notos\Domain\Services\Resource\AssetsManager;
use Bakgat\Notos\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImageController extends Controller
{
    /** @var AssetsManager $assetsManager */
    private $assetsManager;

    public function __construct(AssetsManager $assetsManager)
    {
        parent::__construct();
        $this->assetsManager = $assetsManager;
    }

    public function get($guid)
    {
        $asset = $this->assetsManager->assetOfGuid($guid);
        $path = $this->realPath($asset->path());
        $image = Image::make($path);
        return $image->response();
    }

    private function realPath($path)
    {
        $base = public_path() . '/upload/';
        $path = rtrim($base, '/') . '/' .
            ltrim($path, '/');
        return $path;
    }
}