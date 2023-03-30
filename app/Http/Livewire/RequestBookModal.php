<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\URL;
use LivewireUI\Modal\ModalComponent;

class RequestBookModal extends ModalComponent
{
    public $book;

    public function mount($book) {
        $this->book = Book::findOrFail($book);
    }

    public function render()
    {
        return view('livewire.request-book-modal');
    }
}
