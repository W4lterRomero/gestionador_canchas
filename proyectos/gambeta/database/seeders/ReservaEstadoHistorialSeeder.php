<?php

namespace Database\Seeders;

use App\Models\Reserva;
use App\Models\ReservaEstadoHistorial;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ReservaEstadoHistorialSeeder extends Seeder
{
    /**
     * Registra cambios de estado de ejemplo respetando las llaves foráneas.
     */
    public function run(): void
    {
        $reservaIds = Reserva::pluck('id');
        $usuarioIds = User::pluck('id');

        if ($reservaIds->isEmpty() || $usuarioIds->isEmpty()) {
            return;
        }

        $cambios = [
            [
                'reserva_id' => $reservaIds->random(),
                'estado_anterior' => 'pendiente',
                'estado_nuevo' => 'confirmada',
                'comentario' => 'Cliente pagó anticipo y se confirmó la reserva.',
                'fecha_cambio' => Carbon::now()->subDays(3)->setTime(14, 0),
                'cambiado_por' => $usuarioIds->random(),
            ],
            [
                'reserva_id' => $reservaIds->random(),
                'estado_anterior' => 'confirmada',
                'estado_nuevo' => 'finalizada',
                'comentario' => 'Se registró la finalización del turno sin novedades.',
                'fecha_cambio' => Carbon::now()->subDay()->setTime(22, 0),
                'cambiado_por' => $usuarioIds->random(),
            ],
            [
                'reserva_id' => $reservaIds->random(),
                'estado_anterior' => 'pendiente',
                'estado_nuevo' => 'cancelada',
                'comentario' => 'El cliente solicitó cancelar por mal clima.',
                'fecha_cambio' => Carbon::now()->subDays(2)->setTime(9, 30),
                'cambiado_por' => $usuarioIds->random(),
            ],
        ];

        foreach ($cambios as $cambio) {
            ReservaEstadoHistorial::factory()->fromSeeder($cambio)->create();
        }
    }
}
