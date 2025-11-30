<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\ReservaEstadoHistorial;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservaEstadoHistorial>
 */
class ReservaEstadoHistorialFactory extends Factory
{
    protected $model = ReservaEstadoHistorial::class;
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

        $estadoAnterior = $this->faker->randomElement(Reserva::ESTADOS);
        $estadoNuevoOpciones = array_values(array_diff(Reserva::ESTADOS, [$estadoAnterior]));
        $estadoNuevo = $this->faker->randomElement($estadoNuevoOpciones ?: Reserva::ESTADOS);

        return [
            'reserva_id' => Reserva::factory(),
            'estado_anterior' => $estadoAnterior,
            'estado_nuevo' => $estadoNuevo,
            'comentario' => $this->faker->optional()->sentence(),
            'fecha_cambio' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'cambiado_por' => User::factory(),
        ];
    }

    /**
     * Permite usar datos definidos en seeders sin recurrir a Faker.
     */
    public function fromSeeder(array $attributes): static
    {
        $factory = clone $this;
        $factory->seederData = $attributes;

        return $factory;
    }
}
