<?php

namespace Database\Seeders;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservaSeeder extends Seeder
{
    /**
     * Crea reservas de ejemplo respetando las llaves foráneas.
     */
    public function run(): void
    {
        $canchaIds = Cancha::pluck('id');
        $clienteIds = Cliente::pluck('id');
        $usuarioIds = User::pluck('id');

        if ($canchaIds->isEmpty() || $clienteIds->isEmpty() || $usuarioIds->isEmpty()) {
            return;
        }

        $inicio1 = Carbon::now()->addDays(1)->setTime(18, 0);
        $inicio2 = Carbon::now()->addDays(3)->setTime(20, 30);
        $inicio3 = Carbon::now()->addDays(5)->setTime(10, 0);

        $reservas = [
            [
                'cancha_id' => $canchaIds->random(),
                'cliente_id' => $clienteIds->random(),
                'fecha_reserva' => $inicio1->toDateString(),
                'fecha_inicio' => $inicio1,
                'fecha_fin' => $inicio1->copy()->addMinutes(90),
                'duracion_minutos' => 90,
                'precio_hora' => 35.00,
                'total' => 52.50,
                'estado' => 'confirmada',
                'observaciones' => 'Reserva confirmada para entrenamiento semanal.',
                'creado_por' => $usuarioIds->random(),
                'actualizado_por' => $usuarioIds->random(),
            ],
            [
                'cancha_id' => $canchaIds->random(),
                'cliente_id' => $clienteIds->random(),
                'fecha_reserva' => $inicio2->toDateString(),
                'fecha_inicio' => $inicio2,
                'fecha_fin' => $inicio2->copy()->addMinutes(60),
                'duracion_minutos' => 60,
                'precio_hora' => 28.00,
                'total' => 28.00,
                'estado' => 'pendiente',
                'observaciones' => 'Esperando confirmación del anticipo.',
                'creado_por' => $usuarioIds->random(),
                'actualizado_por' => $usuarioIds->random(),
            ],
            [
                'cancha_id' => $canchaIds->random(),
                'cliente_id' => $clienteIds->random(),
                'fecha_reserva' => $inicio3->toDateString(),
                'fecha_inicio' => $inicio3,
                'fecha_fin' => $inicio3->copy()->addMinutes(120),
                'duracion_minutos' => 120,
                'precio_hora' => 40.00,
                'total' => 80.00,
                'estado' => 'finalizada',
                'observaciones' => 'Bloque para torneo relámpago.',
                'creado_por' => $usuarioIds->random(),
                'actualizado_por' => $usuarioIds->random(),
            ],
        ];

        foreach ($reservas as $reserva) {
            Reserva::factory()->fromSeeder($reserva)->create();
        }
    }
}

