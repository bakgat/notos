<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 16/09/15
 * Time: 08:28
 */

namespace Bakgat\Notos\Http\Controllers\Location;


use Bakgat\Notos\Domain\Model\Location\Blog;
use Bakgat\Notos\Domain\Services\Location\BlogService;
use Bakgat\Notos\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    /** @var BlogService $blogService */
    private $blogService;

    public function __construct(BlogService $blogService)
    {
        parent::__construct();
        $this->blogService = $blogService;
    }

    public function index($orgId)
    {
        $blogs = $this->blogService->all($orgId);
        return $this->jsonResponse($blogs, ['list']);
    }

    public function edit($orgId, $id)
    {
        $blog = $this->blogService->blogOfId($id);
        return $this->jsonResponse($blog, ['detail']);
    }

    public function update($orgId, $id, Request $request)
    {
        $data = $request->all();

        $this->blogService->update($id, $data);
    }

    public function store($orgId, Request $request)
    {
        $data = $request->all();

        $this->blogService->add($orgId, $data);
    }
}