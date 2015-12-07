<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 19/09/15
 * Time: 23:01
 */

namespace Bakgat\Notos\Http\Controllers\Descriptive;


use Bakgat\Notos\Domain\Services\Descriptive\TagService;
use Bakgat\Notos\Http\Controllers\Controller;

class TagController extends Controller
{
    /** @var TagService $tagService */
    private $tagService;

    public function __construct(TagService $tagService)
    {
        parent::__construct();
        $this->tagService = $tagService;
    }

    public function index($type = null)
    {
        if ($type) {
            return $this->jsonResponse($this->tagService->allOfType($type), ['list']);
        } else {
            return $this->jsonResponse($this->tagService->all(), ['list']);
        }
    }

}