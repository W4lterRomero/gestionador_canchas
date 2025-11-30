<?php

namespace App\Livewire;

use App\Models\Cancha;
use Livewire\Component;

class Estadios extends Component
{
    public $search = '';
    public $tipo = '';

    public function getFiltradosProperty()
    {
        return Cancha::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->when($this->tipo, function ($query) {
                $query->where('tipo', $this->tipo);
            })
            ->orderBy('activa', 'desc')
            ->orderBy('nombre', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.estadios');
    }
}
