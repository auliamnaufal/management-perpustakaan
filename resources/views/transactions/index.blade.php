@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card mb-5 rounded-3 p-4" style="background-color: #f7f7f7">
            <h2>Your Profile</h2>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Full Name</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0">{{ auth()->user()->name ?? '' }}h</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">Email</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0">{{ auth()->user()->email ?? '' }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">NISN</p>
                    </div>
                    <div class="col-sm-9">
                        <p class="text-muted mb-0">{{ auth()->user()->student->nisn ?? '-' }}</p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-sm-3">
                        <p class="mb-0">School Year</p>
                    </div>
                    <div class="col-sm-9">
                    <p class="text-muted mb-0">{{ auth()->user()->student->school_year ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-3 p-4" style="background-color: #f7f7f7">
            <h2 class="fw-bold">Your transactions.</h2>
            <table class="table table-hover">
                <caption>List of users</caption>
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pickup Date</th>
                    <th scope="col">Return Date</th>
                    <th scope="col">Books</th>
                    <th scope="col">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $transaction->pickup_date }}</td>
                        <td>{{ $transaction->return_date }}</td>
                        <td>
                            @foreach($transaction->books as $book)
                                <a href="{{ route('books.show', ['id' => $book->id]) }}">{{ $book->title }}</a>
                            @endforeach
                        </td>
                        <td>
                            @if($transaction->is_approved && !$transaction->actual_pickup_date)
                                <p class="p-1 border border-secondary rounded-5 text-center">{{ ucfirst($transaction->is_approved) }}</p>
                            @elseif($transaction->actual_pickup_date && !$transaction->is_returned)
                                <p class="p-1 border border-secondary rounded-5 text-center">Picked up</p>
                            @elseif($transaction->is_returned)
                                <p class="p-1 border border-secondary rounded-5 text-center">Returned</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
