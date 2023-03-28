@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @foreach($books as $book)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <a href="{{ route('books.show', ['id' => $book->id]) }}" class="text-decoration-none text-black">
                        <div class="card border-0 p-2 mb-5">
                            <div class="card-body">
                                <img class="w-100 h-50 mb-2 img-fluid card-img object-fit-cover" src="{{ $book->cover }}"
                                     alt="{{ $book->title }} cover image">
                                <h2 class="h4 text-truncate">{{ $book->title }}</h2>
                                <p class="text-secondary mb-4">{{ $book->shelf->name }}</p>
                                <p class="text-secondary">Stock: {{ $book->stock }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
