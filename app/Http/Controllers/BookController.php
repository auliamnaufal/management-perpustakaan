<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view('books.index', [
            'books' => Book::with('shelf')->get()
        ]);
    }

    public function show(BookService $service, $id)
    {
        $book = $service->findSingleBookWithShelfAndCategory($id);
        $isRequested = $service->checkIfBookIsRequested($id);

        return view('books.show', [
            'book' => $book,
            'isRequested' => $isRequested
        ]);
    }
}
