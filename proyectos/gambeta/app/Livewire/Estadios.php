<?php

namespace App\Livewire;

use Livewire\Component;

class Estadios extends Component
{
    public $search = '';
    public $tipo = '';

    public $estadios = [
        [
            'nombre' => 'Estadio Francisco Barraza',
            'ciudad' => 'San Miguel',
            'tipo' => 'Fútbol rápido',
            'precio' => 45,
            'imagen' => 'images/estadio_barraza.jpg'
        ],
        [
            'nombre' => 'Estadio Félix Charlaix',
            'ciudad' => 'San Miguel',
            'tipo' => 'Fútbol 5',
            'precio' => 35,
            'imagen' => 'images/estadio_charlaix.jpg'
        ],
        [
            'nombre' => 'Estadio Ramón Flores Berríos',
            'ciudad' => 'Santa Rosa de Lima',
            'tipo' => 'Fútbol rápido',
            'precio' => 40,
            'imagen' => 'images/estadio_flores.jpg'
        ],
        [
            'nombre' => 'Estadio Marcelino Imbers',
            'ciudad' => 'La Unión',
            'tipo' => 'Indoor',
            'precio' => 30,
            'imagen' => 'images/estadio_imbers.jpg'
        ],
        [
            'nombre' => 'Estadio Correcaminos',
            'ciudad' => 'San Francisco Gotera',
            'tipo' => 'Fútbol rápido',
            'precio' => 28,
            'imagen' => 'images/estadio_correcaminos.jpg'
        ],
        [
            'nombre' => 'Estadio Municipal Moncagua',
            'ciudad' => 'Moncagua',
            'tipo' => 'Fútbol 5',
            'precio' => 25,
            'imagen' => 'images/estadio_moncagua.jpg'
        ],
    ];

    public function getFiltradosProperty()
    {
        return collect($this->estadios)
            ->filter(fn($e) => stripos($e['nombre'], $this->search) !== false)
            ->filter(fn($e) => !$this->tipo || $e['tipo'] === $this->tipo)
            ->values();
    }

    public function render()
    {
        return view('livewire.estadios');
    }
}
