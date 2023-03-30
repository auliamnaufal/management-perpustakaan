@if(isset($user))
    <x-modal>
        <x-slot name="title">
            Request {{ $book->title }}
        </x-slot>

        <x-slot name="content">
            Hi! 👋
        </x-slot>

        <x-slot name="buttons">
            Buttons go here...
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
            <input id="email" type="text" wire:model.debounce="email" class="form-control mb-2 @if(session()->has('error')) is-invalid @endif" name="email"
                   required autocomplete="email" autofocus placeholder="Email">

            <input type="password" wire:model.debounce="password" class="form-control @if(session()->has('error')) is-invalid @endif" name="password"
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

