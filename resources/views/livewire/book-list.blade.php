<div class="row justify-content-center">
    @foreach($books as $book)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <x-book-card-item :book="$book" />
        </div>
    @endforeach
</div>
