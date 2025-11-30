<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;

class ClienteSeeder extends Seeder
{
    /**
     * Registra clientes base para pruebas.
     */
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Juan Pérez',
                'telefono' => '2222-3333',
                'equipo' => 'Los Halcones',
                'email' => 'juan.perez@example.com',
                'notas' => 'Prefiere horarios nocturnos para amistosos.',
            ],
            [
                'nombre' => 'María Gómez',
                'telefono' => '4444-5555',
                'equipo' => 'Las Guerreras',
                'email' => 'maria.gomez@example.com',
                'notas' => 'Solicita facturación mensual.',
            ],
            [
                'nombre' => 'Club Deportivo Norte',
                'telefono' => '6666-7777',
                'equipo' => 'Juveniles U18',
                'email' => 'contacto@cdnorte.example.com',
                'notas' => 'Reserva habitual para torneos de fin de semana.',
            ],
        ];

        foreach ($clientes as $cliente) {
            Cliente::factory()->fromSeeder($cliente)->create();
        }
    }
}
