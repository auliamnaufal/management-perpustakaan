@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    @foreach($books as $book)
                        <div class="col-lg-4">
                            <a href="{{ route('books.show', ['id' => $book->id]) }}" class="text-decoration-none">
                                <div class="card border-0 p-2 mb-5">
                                    <div class="card-body">
                                        <img class="w-100 h-25 img-fluid card-img" src="{{ $book->cover }}"
                                             alt="{{ $book->title }} cover image">
                                        <h2>{{ $book->title }}</h2>
                                        <p>{{ $book->shelf->name }}</p>
                                        <p>{{ $book->stock }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
@endsection
