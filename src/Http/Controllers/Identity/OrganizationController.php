<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 14/10/15
 * Time: 14:46
 */

namespace Bakgat\Notos\Http\Controllers\Identity;


use Bakgat\Notos\Domain\Model\Identity\Exceptions\OrganizationNotFoundException;
use Bakgat\Notos\Domain\Model\Identity\OrganizationRepository;
use Bakgat\Notos\Http\Controllers\Controller;

class OrganizationController extends Controller
{
    /** @var OrganizationRepository $orgRepo */
    private $orgRepo;

    public function __construct(OrganizationRepository $organizationRepository)
    {
        parent::__construct();
        $this->orgRepo = $organizationRepository;
    }

    /**
     * @param $orgId
     * @return \Bakgat\Notos\Domain\Model\Identity\Organization
     */
    public function edit($orgId)
    {
        $org = $this->orgRepo->organizationOfId($orgId);
        return $this->json($org, ['detail']);

    }
}