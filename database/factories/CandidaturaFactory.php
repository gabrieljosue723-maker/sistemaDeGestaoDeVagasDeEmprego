<?php

namespace Database\Factories;

use App\Models\Candidatura;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Candidatura>
 */
class CandidaturaFactory extends Factory
{
    protected $model = Candidatura::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->candidato(),
            'vaga_id' => Vaga::factory(),
            'status' => fake()->randomElement(Candidatura::statusValidos()),
        ];
    }
}
