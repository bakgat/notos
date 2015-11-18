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
        throw new DuplicateException('isbn', $orgId);
    }

    public function uploadFile(Request $request, $orgId)
    {
        $organization = $this->orgRepo->organizationOfId($orgId);
        if (!$organization) {
            throw new OrganizationNotFoundException($orgId);
        }

        $file = $request->file('asset');
        $asset = $this->assetsManager->upload($file, $organization);
        return $this->jsonResponse($asset, ['detail']);
    }

    public function deleteFile($guid)
    {

    }
}