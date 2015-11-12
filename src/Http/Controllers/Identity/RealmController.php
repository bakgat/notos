<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 25/09/15
 * Time: 18:20
 */

namespace Bakgat\Notos\Http\Controllers\Identity;


use Bakgat\Notos\Domain\Model\Identity\DomainName;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RealmController extends Controller
{
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        parent::__construct();
        $this->orgRepo = $organizationRepository;
    }

    public function get(Request $request)
    {
        if ($request->has('ofDomain')) {
            $realm = $this->orgRepo->organizationOfDomain(new DomainName($request->get('ofDomain')));
            return $this->jsonResponse($realm);
        }
    }
}