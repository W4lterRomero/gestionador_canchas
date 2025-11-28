<?php

namespace Database\Factories;

use App\Models\Cancha;
use App\Models\CanchaPrecio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CanchaPrecio>
 */
class CanchaPrecioFactory extends Factory
{
    protected $model = CanchaPrecio::class;
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

        $fechaDesde = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $fechaHasta = $this->faker->boolean(60)
            ? $this->faker->dateTimeBetween($fechaDesde, '+3 months')
            : null;

        return [
            'cancha_id' => Cancha::factory(),
            'precio_hora' => $this->faker->randomFloat(2, 15, 60),
            'fecha_desde' => $fechaDesde->format('Y-m-d'),
            'fecha_hasta' => $fechaHasta?->format('Y-m-d'),
        ];
    }

    /**
     * Permite usar datos definidos en el seeder sin recurrir a Faker.
     */
    public function fromSeeder(array $attributes): static
    {
        $factory = clone $this;
        $factory->seederData = $attributes;

        return $factory;
    }
}
