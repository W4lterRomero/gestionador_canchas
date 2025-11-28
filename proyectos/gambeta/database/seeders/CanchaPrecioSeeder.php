<?php

namespace Database\Seeders;

use App\Models\CanchaPrecio;
use Illuminate\Database\Seeder;

class CanchaPrecioSeeder extends Seeder
{
    /**
     * Genera historial de precios para las canchas existentes.
     */
    public function run(): void
    {
        $precios = [
            [
                'cancha_id' => 1,
                'precio_hora' => 35.00,
                'fecha_desde' => '2025-01-01',
                'fecha_hasta' => '2025-03-31',
            ],
            [
                'cancha_id' => 2,
                'precio_hora' => 22.50,
                'fecha_desde' => '2025-01-01',
                'fecha_hasta' => '2025-02-28',
            ],
            [
                'cancha_id' => 3,
                'precio_hora' => 40.00,
                'fecha_desde' => '2025-01-01',
                'fecha_hasta' => '2025-04-30',
            ],
            [
                'cancha_id' => 1,
                'precio_hora' => 37.50,
                'fecha_desde' => '2025-04-01',
                'fecha_hasta' => null,
            ],
            [
                'cancha_id' => 2,
                'precio_hora' => 24.00,
                'fecha_desde' => '2025-03-01',
                'fecha_hasta' => null,
            ],
            [
                'cancha_id' => 3,
                'precio_hora' => 42.00,
                'fecha_desde' => '2025-05-01',
                'fecha_hasta' => null,
            ],
        ];

        foreach ($precios as $precio) {
            CanchaPrecio::factory()->fromSeeder($precio)->create();
        }
    }
}
