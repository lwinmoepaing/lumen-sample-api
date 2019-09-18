<?php

namespace App\Http\Controllers;

use App\Book;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BookController extends Controller
{
    use ApiResponser;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //==========================
    // Show Books
    //==========================
    public function index(Request $request)
    {
        $data = Book::paginate(10);
        foreach ($data as $key => $value) {
            $data[$key]['next'] = $this->paginateNextFormat($request, $value);
        }

        return $this->successResponse($data, ['type' => 'paginate']);
    }

    //==========================
    // Create Single Book
    //==========================
    public function store(Request $request)
    {
        $this->validate($request, $this->storeRules());
        $book = Book::create($request->all());
        return $this->successResponse($book, [
            'status' => Response::HTTP_CREATED,
            'more' => $this->moreFormat($request)
        ]);
    }

    //==========================
    // Show Specific Book
    //==========================
    public function show($bookId, Request $request)
    {
        $book = Book::findOrFail($bookId);
        return $this->successResponse($book, [
            'more' => $this->moreFormat($request)
        ]);
    }

    //==========================
    // Update Book
    //==========================
    public function update(Request $request, $bookId)
    {
        $this->validate($request, $this->updateRules());
        $book = Book::findOrFail($bookId);
        $book->fill($request->all());
        if ($book->isClean())
            return $this->errorResponse('At Least One Value Must Change', Response::HTTP_UNPROCESSABLE_ENTITY);

        $book->save();
        return $this->successResponse($book, ['success' => true]);
    }

    //==========================
    // Delete Book
    //==========================
    public function destory($bookId)
    {
        $book = Book::findOrFail($bookId);
        $book->delete();
        return $this->successResponse($book, ['success' => true]);
    }

    //==========================
    // Update Validation
    //==========================
    public function updateRules()
    {
        return [
            'title' => 'max:255',
            'author' => 'min:3 | max:255'
        ];
    }

    //==========================
    // Store Rules
    //==========================
    public function storeRules()
    {
        return [
            'title' => 'required| max:255',
            'author' => 'required| min:3 | max:255'
        ];
    }

    //==========================
    // More Format
    //==========================
    public function moreFormat($request)
    {
        return  [
            'url' => $request->root() . '/books',
            'description' => 'To See All Books',
            'method' => 'GET'
        ];
    }

    //==========================
    // Paginate NExt Format
    //==========================
    public function paginateNextFormat($request, $value)
    {
        return  [
            'developer' => 'Lwin Moe Paing',
            'url' => $request->url() . '/' . $value['id'],
            'method' => 'GET',
            'description' => 'Detail => Title ' . $value['title']
        ];
    }
}
