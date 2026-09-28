<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        return view('site.books.index', [
            'books' => Book::visible()->get(),
        ]);
    }

    public function show(Book $book)
    {
        abort_if($book->status === 'rascunho', 404);

        return view('site.books.show', [
            'book' => $book,
            'others' => Book::visible()->whereKeyNot($book->id)->take(4)->get(),
        ]);
    }
}
