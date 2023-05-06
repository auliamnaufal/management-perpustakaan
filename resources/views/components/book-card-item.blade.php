@props([
  'book'
])

<a href="{{ route('books.show', ['id' => $book->id]) }}" class="text-decoration-none text-black">
    <div class="card border-0 p-2 mb-5" style="background-color: #f2f5f3">
        <div class="card-body">
            <img class="w-100 h-50 mb-2 img-fluid card-img" style="min-height: 195px; object-fit: cover; object-position: center" src="{{ str_starts_with($book->cover, 'http') ? $book->cover : asset('storage/' . $book->cover) }}"
                 alt="{{ $book->title }} cover image">
            <h2 class="h4 text-truncate">{{ $book->title }}</h2>
            <p class="text-secondary mb-4">{{ $book->shelf->name }}</p>
            <p class="text-secondary">Stock: {{ $book->stock }}</p>
        </div>
    </div>
</a>
