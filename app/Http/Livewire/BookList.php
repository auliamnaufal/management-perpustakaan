<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;

class BookList extends Component
{
    public $books;
    public $query;

    public function mount()
    {
        $this->books = Book::all();
    }

    public function updatedQuery() {
        $this->books = Book::query()
            ->where('title', 'like', '%' . $this->query . '%')
            ->get();

        info(json_encode($this->books));
    }

    public function render()
    {
        return view('livewire.book-list');
    }
}
