<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Transaction;

class BookService
{

    public function findSingleBookWithShelfAndCategory($bookId): Book
    {
        return Book::with('shelf', 'category')->findOrFail($bookId);
    }

    public function checkIfBookIsRequested(int $bookId): bool
    {
        $isRequested = false;

        if (auth()->check()) {
            $isRequested = (bool)Transaction::query()
                ->where('email', auth()->user()->email)
                ->where('is_returned', 0)
                ->whereHas('books', function ($q) use ($bookId) {
                    $q->where('book_id', $bookId);
                })
                ->count();

        }

        return $isRequested;
    }
}
