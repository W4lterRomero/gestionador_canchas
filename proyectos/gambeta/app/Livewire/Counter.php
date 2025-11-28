<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    // Propiedad pública = se sincroniza automáticamente con la vista
    public $count = 0;

    // Método que se ejecuta al hacer click
    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
