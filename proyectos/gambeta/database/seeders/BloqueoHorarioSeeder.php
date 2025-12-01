<?php

namespace Database\Seeders;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\User;
use Illuminate\Database\Seeder;

class BloqueoHorarioSeeder extends Seeder
{
    /**
     * Genera ejemplos de bloqueos de horarios para las canchas.
     */
    public function run(): void
    {
        $canchaIds = Cancha::pluck('id', 'nombre');
        $adminUser = User::whereHas('role', function ($query) {
            $query->where('nombre', 'Administrador');
        })->first();

        if ($canchaIds->isEmpty()) {
            return;
        }

        if ($adminUser === null) {
            $adminUser = User::factory()->admin()->create([
                'nombre' => 'Administrador Bloqueos',
                'email' => 'admin.bloqueos.' . uniqid() . '@example.com',
            ]);
        }

        $fechas = [
            [
                'inicio' => now()->copy()->addDay()->setTime(9, 0),
                'fin' => now()->copy()->addDay()->setTime(11, 0),
                'motivo' => 'Mantenimiento semanal de césped.',
                'cancha_id' => $canchaIds->get('Cancha Central') ?? $canchaIds->first(),
            ],
            [
                'inicio' => now()->copy()->addDays(2)->setTime(18, 0),
                'fin' => now()->copy()->addDays(2)->setTime(20, 0),
                'motivo' => 'Reserva corporativa exclusiva.',
                'cancha_id' => $canchaIds->get('Estadio Chico') ?? $canchaIds->first(),
            ],
            [
                'inicio' => now()->copy()->addDays(3)->setTime(7, 30),
                'fin' => now()->copy()->addDays(3)->setTime(9, 30),
                'motivo' => 'Evento comunitario matutino.',
                'cancha_id' => $canchaIds->get('Arena Indoor') ?? $canchaIds->first(),
            ],
        ];

        foreach ($fechas as $bloqueo) {
            BloqueoHorario::factory()->create([
                'cancha_id' => $bloqueo['cancha_id'],
                'fecha_inicio' => $bloqueo['inicio'],
                'fecha_fin' => $bloqueo['fin'],
                'motivo' => $bloqueo['motivo'],
                'creado_por' => $adminUser->id,
            ]);
        }
    }
}
