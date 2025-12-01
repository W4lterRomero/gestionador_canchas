<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cliente>
 */
class ClienteFactory extends Factory
{
    protected $model = Cliente::class;
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
            'nombre' => $this->faker->name(),
            'telefono' => $this->faker->unique()->e164PhoneNumber(),
            'equipo' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'notas' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Permite usar datos definidos en el seeder sin Faker.
     */
    public function fromSeeder(array $attributes): static
    {
        $factory = clone $this;
        $factory->seederData = $attributes;

        return $factory;
    }
}
