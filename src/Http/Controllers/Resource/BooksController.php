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
use Illuminate\Http\Request;

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
        $books = $this->bookService->all($orgId);
        return $this->jsonResponse($books);
    }

    public function edit($orgId, $id)
    {
        $book = $this->bookService->bookOfId($id);
        return $this->jsonResponse($book);
    }

    public function update(Request $request, $orgId, $id)
    {
        $book = $this->bookService->update($id, $request->all());
        return $this->jsonResponse($book);
    }

    public function store(Request $request, $orgId)
    {
        $book = $this->bookService->add($orgId, $request->all());
        return $this->jsonResponse($book);
    }

    public function destroy(Request $request, $orgId)
    {

    }
}