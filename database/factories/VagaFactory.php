<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vaga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vaga>
 */
class VagaFactory extends Factory
{
    protected $model = Vaga::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->empresa(),
            'titulo' => fake()->jobTitle(),
            'descricao' => fake()->paragraphs(3, true),
            'localizacao' => fake()->city(),
        ];
    }
}
