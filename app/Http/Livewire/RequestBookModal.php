<?php

namespace App\Http\Livewire;

use LivewireUI\Modal\ModalComponent;

class RequestBookModal extends ModalComponent
{
    public function render()
    {
        return view('livewire.request-book-modal');
    }
}
