<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('books.index', [
            'books' => Book::with('shelf')->get()
        ]);
    }

    public function show($id)
    {
        dd(Book::with('shelf', 'category')->findOrFail($id));
        return view('books.index', [
            'books' => Book::with('shelf')->get()
        ]);
    }
}
