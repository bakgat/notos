<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 16:53
 */

namespace Bakgat\Notos\Http\Controllers\Resource;


use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Domain\Services\Resource\AssetsManager;
use Bakgat\Notos\Exceptions\DuplicateException;
use Bakgat\Notos\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class AssetsController extends Controller
{
    /** @var AssetsManager $assetsManager */
    private $assetsManager;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(AssetsManager $assetsManager, OrganizationRepository $organizationRepository)
    {
        parent::__construct();
        $this->assetsManager = $assetsManager;
        $this->orgRepo = $organizationRepository;
    }

    public function index($orgId)
    {
        $assets = $this->assetsManager->all($orgId);
        return $this->jsonResponse($assets);
    }

    public function ofMime($orgId, $mime)
    {
        $assets = $this->assetsManager->assetsOfMimePart($orgId, $mime);
        return $this->jsonResponse($assets);
    }
    public function ofMimeAndType($orgId, $mime, $type)
    {
        $assets = $this->assetsManager->assetsOfMimePartAndType($orgId, $mime, $type);
        return $this->jsonResponse($assets);
    }
    public function imagesForWebsite() {
        $images = $this->assetsManager->imagesForWebsites();
        return $this->jsonResponse($images);
    }

    public function uploadFile(Request $request, $orgId = null)
    {
        $files = [];
        $response = [];

        if (!$request->file('file')) {
            throw new UploadException('File not available');
        }
        if (is_array($request->file('file'))) {
            $files = $request->file('file');
        } else {
            if (!$request->file('file')->isValid()) {
                throw new UploadException('File not available');
            }
            $files = [$request->file('file')];
        }

        foreach ($files as $file) {
            $asset = $this->assetsManager->upload($file, $orgId);
            $response[] = $asset;
        }
        return $this->jsonResponse($response, ['detail']);
    }

    public function importUrl(Request $request, $orgId = null)
    {
        if (!$request->get('url')) {
            throw new \InvalidArgumentException('URL nog set');
        }

        $asset = $this->assetsManager->import($request->get('url'), $orgId);
        return $this->jsonResponse($asset, ['detail']);
    }

    public function deleteFile($guid)
    {

    }
}