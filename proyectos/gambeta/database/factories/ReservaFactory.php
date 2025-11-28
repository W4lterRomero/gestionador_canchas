<?php

namespace Database\Factories;

use App\Models\Cancha;
use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reserva>
 */
class ReservaFactory extends Factory
{
    protected $model = Reserva::class;
    protected ?array $seederData = null;

    /**
     * Define el estado por defecto del modelo.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if ($this->seederData !== null) {
            return $this->seederData;
        }

        $fechaInicio = $this->faker->dateTimeBetween('+1 day', '+1 month');
        $duracion = $this->faker->randomElement([60, 90, 120]);
        $fechaFin = (clone $fechaInicio)->modify("+{$duracion} minutes");
        $precioHora = $this->faker->randomFloat(2, 15, 50);
        $total = round($precioHora * ($duracion / 60), 2);

        return [
            'cancha_id' => Cancha::factory(),
            'cliente_id' => Cliente::factory(),
            'fecha_reserva' => $fechaInicio->format('Y-m-d'),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'duracion_minutos' => $duracion,
            'precio_hora' => $precioHora,
            'total' => $total,
            'estado' => $this->faker->randomElement(Reserva::ESTADOS),
            'observaciones' => $this->faker->optional()->sentence(),
            'creado_por' => User::factory(),
            'actualizado_por' => User::factory(),
        ];
    }

    /**
     * Permite usar datos específicos definidos en seeders.
     */
    public function fromSeeder(array $attributes): static
    {
        $factory = clone $this;
        $factory->seederData = $attributes;

        return $factory;
    }
}

