<?php

namespace Database\Seeders;

use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PagoSeeder extends Seeder
{
    /**
     * Crea pagos de ejemplo respetando las llaves foráneas.
     */
    public function run(): void
    {
        $reservaIds = Reserva::pluck('id');
        $usuarioIds = User::pluck('id');

        if ($reservaIds->isEmpty() || $usuarioIds->isEmpty()) {
            return;
        }

        $pagos = [
            [
                'reserva_id' => $reservaIds->random(),
                'monto' => 30.00,
                'tipo_pago' => 'anticipo',
                'metodo_pago' => 'transferencia',
                'fecha_pago' => Carbon::now()->subDays(1)->setTime(15, 0),
                'comprobante_url' => 'https://example.com/comprobantes/pago1.pdf',
                'registrado_por' => $usuarioIds->random(),
            ],
            [
                'reserva_id' => $reservaIds->random(),
                'monto' => 50.00,
                'tipo_pago' => 'saldo',
                'metodo_pago' => 'efectivo',
                'fecha_pago' => Carbon::now()->subDays(2)->setTime(19, 30),
                'comprobante_url' => 'https://example.com/comprobantes/pago2.pdf',
                'registrado_por' => $usuarioIds->random(),
            ],
            [
                'reserva_id' => $reservaIds->random(),
                'monto' => 80.00,
                'tipo_pago' => 'total',
                'metodo_pago' => 'tarjeta',
                'fecha_pago' => Carbon::now()->subDays(3)->setTime(10, 15),
                'comprobante_url' => 'https://example.com/comprobantes/pago3.pdf',
                'registrado_por' => $usuarioIds->random(),
            ],
        ];

        foreach ($pagos as $pago) {
            Pago::factory()->fromSeeder($pago)->create();
        }
    }
}

