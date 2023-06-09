<?php

namespace App\Http\Livewire;

use App\Models\Book;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use LivewireUI\Modal\ModalComponent;

class RequestBookModal extends ModalComponent
{
    public $book;
    public $user;

    public $email;
    public $password;

    public $pickupDate;
    public $returnDate;

    public $isBorrowed = false;
    public $isRequested;

    public function mount($book) {
        $this->book = Book::findOrFail($book);
        $this->user = auth()->user();

        if (auth()->check()) {
            $this->isBorrowed = $this->isRequested;

        }
    }

    public function render()
    {
        return view('livewire.request-book-modal');
    }

    public function login() {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->flash('success', 'You logged in successfully');
            return redirect()->to(route('books.show', ['id' => $this->book->id]));

        } else  {
            session()->flash('error', 'Please insert the correct credentials');
        }
    }

    public function requestBook() {
        $this->validate([
            'pickupDate' => 'required|date',
            'returnDate' => ['required', 'date', 'after:' . $this->pickupDate]
        ]);

        $transaction = Transaction::create([
            'full_name' => $this->user->name,
            'email' => $this->user->email,
            'nisn' => $this->user->student->nisn,
            'class' => $this->user->student->class,
            'pickup_date' => $this->pickupDate,
            'return_date' => $this->returnDate,
            'is_returned' => false
        ]);

        $transaction->books()->attach([$this->book->id]);

        session()->flash('success', 'Book borrow successfully requested.');
        return redirect()->to(route('books.show', ['id' => $this->book->id]));

    }
}
