<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 08:28
 */

namespace Bakgat\Notos\Http\Controllers\Location;


use Bakgat\Notos\Domain\Model\Location\Exceptions\URLNotValidException;
use Bakgat\Notos\Domain\Model\Location\URL;
use Bakgat\Notos\Domain\Services\Location\WebsitesService;
use Bakgat\Notos\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebsitesController extends Controller
{
    /** @var WebsitesService $websitesService */
    private $websitesService;

    public function __construct(WebsitesService $websitesService)
    {
        parent::__construct();
        $this->websitesService = $websitesService;
    }

    public function index()
    {
        return $this->jsonResponse($this->websitesService->all(), ['list']);
    }

    public function fullIndex()
    {
        return $this->jsonResponse($this->websitesService->full(), ['full', 'list']);
    }

    public function edit($id)
    {
        return $this->jsonResponse($this->websitesService->websiteOfId($id), ['detail']);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $website = $this->websitesService->add($data);

        return $this->jsonResponse($website);
    }

    public function update($id, Request $request)
    {
        $data = $request->all();
        $website = $this->websitesService->update($id, $data);

        return $this->jsonResponse($website);
    }

    public function suggest(Request $request)
    {
        $data = $request->all();

        $website = $this->websitesService->suggest($data);

        return $this->jsonResponse($website);
    }

    public function ofUrl(Request $request)
    {
        $url = new URL($request->get('url'));
        $website = $this->websitesService->checkURL($url);
        if ($website) {
            return $this->jsonResponse($website);
        }
    }
}