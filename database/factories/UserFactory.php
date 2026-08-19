<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'tipo' => fake()->randomElement(['Candidato', 'Empresa']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Utilizador do tipo Candidato, com perfil profissional opcionalmente preenchido.
     */
    public function candidato(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Candidato',
            'anos_experiencia' => fake()->numberBetween(0, 15),
            'localizacao' => fake()->city(),
            'formacao' => fake()->randomElement([
                'Licenciatura em Informática',
                'Licenciatura em Gestão',
                'Ensino Secundário',
                'Mestrado em Engenharia',
            ]),
            'disponibilidade' => fake()->randomElement(['imediata', 'a_combinar', 'part_time', 'full_time']),
            'bio' => fake()->sentence(15),
        ]);
    }

    /**
     * Utilizador do tipo Empresa.
     */
    public function empresa(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Empresa',
        ]);
    }
}
