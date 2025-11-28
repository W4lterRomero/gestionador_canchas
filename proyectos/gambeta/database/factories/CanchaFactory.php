<?php

namespace Database\Factories;

use App\Models\Cancha;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Cancha>
 */
class CanchaFactory extends Factory
{
    protected $model = Cancha::class;
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

        $tipos = ['Fútbol 5', 'Fútbol 8', 'Sintética', 'Indoor', 'Mixta'];

        return [
            'nombre' => 'Cancha ' . $this->faker->unique()->citySuffix(),
            'tipo' => $this->faker->randomElement($tipos),
            'descripcion' => $this->faker->paragraph(),
            'precio_hora' => $this->faker->randomFloat(2, 10, 45),
            'imagen_url' => 'resources/images/cancha.png',
            'activa' => $this->faker->boolean(80),
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
