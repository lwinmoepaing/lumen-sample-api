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

    public function index()
    {
        $data = Book::all();
        return $this->successResponse($data);
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required| max:255',
            'author' => 'required| min:3 | max:255'
        ];
        $this->validate($request, $rules);

        $book = Book::create($request->all());
        return $this->successResponse($book, null, Response::HTTP_CREATED);
    }


    public function show($bookId)
    {
        $book = Book::findOrFail($bookId);
        return $this->successResponse($book);
    }

    public function update(Request $request, $bookId)
    {
        $rules = [
            'title' => 'max:255',
            'author' => 'min:3 | max:255'
        ];
        $this->validate($request, $rules);

        $book = Book::findOrFail($bookId);
        $book->fill($request->all());

        if ($book->isClean()) {
            return $this->errorResponse('At Least One Value Must Change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $book->save();
        return $this->successResponse($book, ["success" => true]);
    }

    public function destory($bookId)
    {
        $book = Book::findOrFail($bookId);
        $book->delete();
        return $this->successResponse($book, ["success" => true]);
    }
}
