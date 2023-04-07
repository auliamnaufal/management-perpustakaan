<div>
    <div class="row mb-5 px-1">
        <div class="col-2 offset-10">
            <input type="text" class="form-control" placeholder="Type here to search.."
                   wire:model.debounce.200ms="query">
        </div>
    </div>
    <div class="row justify-content-center">
        @foreach($books as $book)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <x-book-card-item :book="$book"/>
            </div>
        @endforeach
    </div>
</div>
