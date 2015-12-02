<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 1/12/15
 * Time: 16:48
 */

namespace Bakgat\Notos\Http\Controllers\Identity;


use Bakgat\Notos\Domain\Model\Identity\PartyRepository;
use Bakgat\Notos\Domain\Model\KindRepository;
use Bakgat\Notos\Http\Controllers\Controller;

class PartyController extends Controller
{
    /** @var PartyRepository $partyRepo */
    private $partyRepo;
    /** @var KindRepository $kindRepo */
    private $kindRepo;

    public function __construct(PartyRepository $partyRepository,
                                KindRepository $kindRepository)
    {

        parent::__construct();
        //$this->middleware('auth');
        $this->kindRepo = $kindRepository;
        $this->partyRepo = $partyRepository;
    }

    public function authors()
    {
        $kind = $this->kindRepo->get('author');
        $parties = $this->partyRepo->partiesOfKind($kind);
        return $this->jsonResponse($parties, ['detail']);
    }

    public function publishers()
    {
        $kind = $this->kindRepo->get('publisher');
        $parties = $this->partyRepo->partiesOfKind($kind);
        return $this->jsonResponse($parties);
    }
}