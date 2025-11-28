<?php

namespace Database\Factories;

use App\Models\BloqueoHorario;
use App\Models\Cancha;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends Factory<BloqueoHorario>
 */
class BloqueoHorarioFactory extends Factory
{
    protected $model = BloqueoHorario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $inicio = Carbon::instance($this->faker->dateTimeBetween('now', '+1 week'));

        return [
            'cancha_id' => Cancha::factory(),
            'fecha_inicio' => $inicio,
            'fecha_fin' => (clone $inicio)->addHours($this->faker->numberBetween(1, 4)),
            'motivo' => $this->faker->sentence(6),
            'creado_por' => User::factory()->admin(),
        ];
    }
}
