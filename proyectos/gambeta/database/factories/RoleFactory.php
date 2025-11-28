<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;
    protected ?array $seederData = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if ($this->seederData !== null) {
            return $this->seederData;
        }

        $roles = ['Administrador', 'Empleado'];

        return [
            'nombre' => $this->faker->unique()->randomElement($roles),
            'descripcion' => $this->faker->sentence(),
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
