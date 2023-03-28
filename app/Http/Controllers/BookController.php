<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
        return view('books.show', [
            'book' => Book::with('shelf', 'category')->findOrFail($id)
        ]);
    }
}
