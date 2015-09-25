<?php
/**
 * Created by PhpStorm.
 * User: karlvaniseghem
 * Date: 22/09/15
 * Time: 17:01
 */

namespace Bakgat\Notos\Http\Controllers\Resource;


use Bakgat\Notos\Domain\Services\Resource\BookService;
use Bakgat\Notos\Http\Controllers\Controller;

class BooksController extends Controller
{
    /** @var BookService $bookService */
    private $bookService;

    public function __construct(BookService $bookService)
    {
        parent::__construct();
        $this->bookService = $bookService;
    }

    public function index($orgId)
    {
        return $this->jsonResponse($this->bookService->all($orgId));
    }
}