@props(['formAction' => false])

<div>
    @if($formAction)
        <form wire:submit.prevent="{{ $formAction }}">
            @endif
            <div class="bg-white pt-4 pb-2 px-4 d-flex flex-column align-items-center justify-content-center">
                @if(isset($title))
                    <h3 class="text-center leading-6 font-medium text-gray-900">
                        {{ $title }}
                    </h3>
                @endif
                @if(isset($subTitle))
                    <p class="text-center">
                        {{ $subTitle }}
                    </p>
                @endif
            </div>
            <div class="bg-white px-4 sm:p-6">
                <div class="space-y-6">
                    {{ $content }}
                </div>
            </div>

            <div class="bg-white px-4 pb-3 d-flex flex-column align-items-center justify-content-center sm:px-4 sm:flex">
                {{ $buttons }}
            </div>
            @if($formAction)
        </form>
    @endif
</div>
