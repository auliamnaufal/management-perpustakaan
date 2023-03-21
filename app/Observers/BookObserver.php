<?php

namespace App\Observers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookObserver
{
    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        if ($book->isDirty('cover')) {
            Storage::disk('public')->delete($book->getOriginal('cover'));
        }
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        if (! is_null($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        //
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        //
    }
}
