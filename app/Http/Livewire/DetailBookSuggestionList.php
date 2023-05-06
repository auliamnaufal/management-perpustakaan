<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Livewire\Component;

class DetailBookSuggestionList extends Component
{
    public $books;

    public function mount($categoryId) {
        $this->books = Book::query()->where('category_id', $categoryId)->limit(20)->get();
    }

    public function render()
    {
        return view('livewire.detail-book-suggestion-list');
    }
}
