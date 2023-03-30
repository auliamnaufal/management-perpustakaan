<?php

namespace App\Http\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use LivewireUI\Modal\ModalComponent;

class RequestBookModal extends ModalComponent
{
    public $book;
    public $user;

    public $email;
    public $password;

    public function mount($book) {
        $this->book = Book::findOrFail($book);
        $this->user = auth()->user();
    }

    public function render()
    {
        return view('livewire.request-book-modal');
    }

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];

    public function login() {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('success', 'You logged in successfully');
            return redirect()->to(route('books.show', ['id' => $this->book->id]));

        } else  {
            session()->flash('error', 'Please insert the correct credentials');
        }

    }
}
