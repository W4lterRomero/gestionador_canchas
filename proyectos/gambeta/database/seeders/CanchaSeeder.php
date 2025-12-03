<?php

namespace Database\Seeders;

use App\Models\Cancha;
use Illuminate\Database\Seeder;

class CanchaSeeder extends Seeder
{
    /**
     * Genera canchas de ejemplo para el sistema.
     */
    public function run(): void
    {
        $canchas = [
            [
                'nombre' => 'Cancha Central',
                'tipo' => 'Sintética',
                'descripcion' => 'Campo techado ideal para torneos entre semana.',
                'precio_hora' => 35.00,
                'imagen_url' => '/storage/canchas/cancha.png',
                'activa' => true,
            ],
            [
                'nombre' => 'Estadio Chico',
                'tipo' => 'Fútbol 5',
                'descripcion' => 'Espacio abierto con césped natural para grupos pequeños.',
                'precio_hora' => 22.50,
                'imagen_url' => '/storage/canchas/cancha.png',
                'activa' => true,
            ],
            [
                'nombre' => 'Arena Indoor',
                'tipo' => 'Indoor',
                'descripcion' => 'Cancha climatizada perfecta para la temporada de lluvia.',
                'precio_hora' => 40.00,
                'imagen_url' => '/storage/canchas/cancha.png',
                'activa' => false,
            ],
        ];

        foreach ($canchas as $data) {
            $cancha = Cancha::factory()->fromSeeder($data)->create();

            // Agregar imágenes de galería de ejemplo
            $cancha->imagenes()->createMany([
                ['imagen_url' => '/storage/canchas/cancha.png'],
                ['imagen_url' => '/storage/canchas/cancha.png'],
                ['imagen_url' => '/storage/canchas/cancha.png'],
            ]);
        }
    }
}
