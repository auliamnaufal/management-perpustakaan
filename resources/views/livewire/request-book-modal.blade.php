@if(isset($user))
    <x-modal form-action="requestBook">
        <x-slot name="title">
            Borrow {{ $book->title }}
        </x-slot>

        <x-slot name="subTitle">
            Please insert borrowing details
        </x-slot>

        <x-slot name="content">
            <div class="form-group mb-2" style="display: flex; flex-direction: column; align-items: start">
                <label for="pickup_date" class="mb-2">Insert Pickup Date</label>
                <input id="pickup_date" type="datetime-local" wire:model="pickupDate"
                       class="form-control mb-2 @error('pickupDate') is-invalid @enderror" name="pickup_date"
                       required autocomplete="email" autofocus placeholder="Pickup Date">
                @error('pickupDate')
                    <span class="invalid-feedback " role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; align-items: start">
                <label for="return_date" class="mb-2">Insert Return Date</label>
                <input type="datetime-local" wire:model="returnDate"
                       class="form-control @error('returnDate') is-invalid @enderror" name="return_date"
                       placeholder="Return Date">
                @error('returnDate')
                    <span class="invalid-feedback " role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </x-slot>

        <x-slot name="buttons">
            <button class="btn btn-success btn-lg mt-3">Request</button>
        </x-slot>
    </x-modal>

@else
    <x-modal form-action="login">
        <x-slot name="title">
            Login
        </x-slot>

        <x-slot name="subTitle">
            Please login first
        </x-slot>

        <x-slot name="content">
            <input id="email" type="text" wire:model.debounce="email"
                   class="form-control mb-2 @if(session()->has('error')) is-invalid @endif" name="email"
                   required autocomplete="email" autofocus placeholder="Email">

            <input type="password" wire:model.debounce="password"
                   class="form-control @if(session()->has('error')) is-invalid @endif" name="password"
                   placeholder="Password">

            @if (session()->has('error'))
                <span class="invalid-feedback " role="alert">
                    <strong>{{ session('error') }}</strong>
                </span>
            @endif
        </x-slot>

        <x-slot name="buttons">
            <button class="btn btn-primary btn-lg mt-3">Login</button>
            <p class="mt-2">Don't have an account? <a href="{{ route('register') }}">Register</a></p>
        </x-slot>
    </x-modal>

@endif

