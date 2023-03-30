<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return view('books.index', [
            'books' => Book::with('shelf')->get()
        ]);
    }

    public function show($id)
    {
        $book = Book::with('shelf', 'category')->findOrFail($id);
        $isRequested = false;

        if (auth()->check()) {
            $isRequested = (bool)Transaction::query()
                ->where('book_id', $book->id)
                ->where('email', auth()->user()->email)
                ->where('is_returned', 0)
                ->first();
        }

        return view('books.show', [
            'book' => $book,
            'isRequested' => $isRequested
        ]);
    }
}
