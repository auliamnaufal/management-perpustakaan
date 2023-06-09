<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [\App\Http\Controllers\BookController::class, 'show'])->name('books.show');
Route::get('/transactions', \App\Http\Controllers\TransactionController::class)->name('transaction.index');

Auth::routes();


