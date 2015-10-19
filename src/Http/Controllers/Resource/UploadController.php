<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 13/10/15
 * Time: 16:53
 */

namespace Bakgat\Notos\Http\Controllers\Resource;


use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    /** @var AssetsManager $assetsManager */
    private $assetsManager;
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(AssetsManager $assetsManager, OrganizationRepository $organizationRepository)
    {
        $this->assetsManager = $assetsManager;
        $this->orgRepo = $organizationRepository;
    }

    public function uploadFile(Request $request, $orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if ($organization) {
            $file = $request->file('asset');
            $asset = $this->assetsManager->upload($file, $organization);
            return $asset;
        } else {
            return new OrganizationNotFoundException($orgId);
        }
    }

    public function deleteFile($guid)
    {

    }
}