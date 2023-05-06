<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke()
    {
        $user = auth()->user();
        $transactions = Transaction::with('books')->where('email', $user->email)->orderBy('created_at', 'DESC')->get();

        return view('transactions.index', compact('transactions'));
    }
}
