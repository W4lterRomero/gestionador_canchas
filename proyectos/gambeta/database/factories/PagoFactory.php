<?php

namespace Database\Factories;

use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pago>
 */
class PagoFactory extends Factory
{
    protected $model = Pago::class;
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

        return [
            'reserva_id' => Reserva::factory(),
            'monto' => $this->faker->randomFloat(2, 20, 200),
            'tipo_pago' => $this->faker->randomElement(Pago::TIPOS),
            'metodo_pago' => $this->faker->randomElement(['efectivo', 'transferencia', 'tarjeta']),
            'fecha_pago' => $this->faker->dateTimeBetween('-1 week', 'now'),
            'comprobante_url' => $this->faker->url(),
            'registrado_por' => User::factory(),
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

