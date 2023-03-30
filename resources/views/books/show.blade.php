@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-4">
                        <img class="w-100 h-100 img-fluid rounded-1" src="{{ str_starts_with($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover)  }}"
                             alt="{{ $book->title }} cover image">
                    </div>
                    <div class="col-md-8 d-flex flex-column justify-content-between">
                        <div>
                            <h1 class="fw-bold">{{ $book->title }}</h1>
                            <p class="lead">{{ $book->shelf->name }}</p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center w-25">
                            <button class="btn btn-success" onclick="Livewire.emit('openModal', 'request-book-modal', {{ json_encode(["book" => $book->id]) }})">Request</button>
                            <p class="m-0">Stock: {{ $book->stock }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('success'))
            <div class="toast show" style="position: absolute; top: 70px; right: 20px;">
                <div class="toast-header">
                    <strong class="mr-auto">Perpustakaan 👋</strong>
                </div>
                <div class="toast-body">
                    You are successfully logged in
                </div>
            </div>
            <script>
                setTimeout(function () {
                    document.querySelector('.show').classList.remove('show')
                }, 2000)
            </script>
    @endif
@endsection
