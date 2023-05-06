<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class BookList extends Component
{
    use WithPagination;

    public $query;

    public function updatingQuery() {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.book-list', [
            'books' => Book::query()
                ->where('title', 'like', '%' . $this->query . '%')
                ->paginate(10)
        ]);
    }
}
