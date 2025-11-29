<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Cancha;
use Carbon\Carbon;

class ReservasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear clientes de prueba
        $clientes = [
            [
                'nombre' => 'Carlos Martínez',
                'telefono' => '8888-1111',
                'email' => 'carlos.martinez@email.com',
                'equipo' => 'Los Relámpagos FC',
            ],
            [
                'nombre' => 'Ana García',
                'telefono' => '8888-2222',
                'email' => 'ana.garcia@email.com',
                'equipo' => 'Las Águilas',
            ],
            [
                'nombre' => 'Roberto López',
                'telefono' => '8888-3333',
                'email' => 'roberto.lopez@email.com',
                'equipo' => 'Los Tigres',
            ],
            [
                'nombre' => 'María Rodríguez',
                'telefono' => '8888-4444',
                'email' => 'maria.rodriguez@email.com',
                'equipo' => 'Las Leonas',
            ],
            [
                'nombre' => 'José Hernández',
                'telefono' => '8888-5555',
                'email' => 'jose.hernandez@email.com',
                'equipo' => 'Los Campeones',
            ],
        ];

        foreach ($clientes as $clienteData) {
            Cliente::firstOrCreate(
                ['email' => $clienteData['email']],
                $clienteData
            );
        }

        // Obtener todos los clientes
        $clientesCreados = Cliente::all();

        // Obtener la primera cancha (puedes cambiar el ID según necesites)
        $cancha = Cancha::first();

        if (!$cancha) {
            $this->command->error('No hay canchas en la base de datos');
            return;
        }

        // Crear reservas de los últimos 30 días y próximos 15 días
        $reservas = [];

        // Reservas pasadas (últimos 30 días)
        for ($i = 30; $i > 0; $i--) {
            $fecha = Carbon::now()->subDays($i);

            // Crear 2-3 reservas por día aleatorias
            $numReservas = rand(0, 3);

            for ($j = 0; $j < $numReservas; $j++) {
                $horaInicio = rand(8, 19); // Entre 8 AM y 7 PM
                $duracionMinutos = [60, 90, 120][rand(0, 2)]; // 1, 1.5 o 2 horas

                $fechaInicio = $fecha->copy()->setTime($horaInicio, 0, 0);
                $fechaFin = $fechaInicio->copy()->addMinutes($duracionMinutos);

                $cliente = $clientesCreados->random();

                $estados = ['confirmada', 'finalizada', 'cancelada'];
                $estado = $i > 5 ? 'finalizada' : $estados[rand(0, 2)];

                $total = ($cancha->precio_hora / 60) * $duracionMinutos;

                $observaciones = [
                    null,
                    'Partido amistoso',
                    'Torneo local',
                    'Entrenamiento',
                    'Cumpleaños',
                ];

                Reserva::create([
                    'cancha_id' => $cancha->id,
                    'cliente_id' => $cliente->id,
                    'fecha_reserva' => $fecha->copy()->subDays(rand(1, 7)),
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'duracion_minutos' => $duracionMinutos,
                    'precio_hora' => $cancha->precio_hora,
                    'total' => $total,
                    'estado' => $estado,
                    'observaciones' => $observaciones[rand(0, 4)],
                    'creado_por' => 1,
                    'actualizado_por' => 1,
                ]);
            }
        }

        // Reservas futuras (próximos 15 días)
        for ($i = 1; $i <= 15; $i++) {
            $fecha = Carbon::now()->addDays($i);

            // Crear 1-2 reservas futuras por día
            $numReservas = rand(1, 2);

            for ($j = 0; $j < $numReservas; $j++) {
                $horaInicio = rand(8, 19);
                $duracionMinutos = [60, 90, 120][rand(0, 2)];

                $fechaInicio = $fecha->copy()->setTime($horaInicio, 0, 0);
                $fechaFin = $fechaInicio->copy()->addMinutes($duracionMinutos);

                $cliente = $clientesCreados->random();

                $estados = ['pendiente', 'confirmada'];
                $estado = $estados[rand(0, 1)];

                $total = ($cancha->precio_hora / 60) * $duracionMinutos;

                Reserva::create([
                    'cancha_id' => $cancha->id,
                    'cliente_id' => $cliente->id,
                    'fecha_reserva' => Carbon::now(),
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'duracion_minutos' => $duracionMinutos,
                    'precio_hora' => $cancha->precio_hora,
                    'total' => $total,
                    'estado' => $estado,
                    'observaciones' => null,
                    'creado_por' => 1,
                    'actualizado_por' => 1,
                ]);
            }
        }

        $this->command->info('✅ Clientes y reservas de prueba creadas exitosamente');
    }
}
