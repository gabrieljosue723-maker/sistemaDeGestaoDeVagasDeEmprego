<?php

namespace Database\Seeders;

use App\Models\Candidatura;
use App\Models\Skill;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SkillSeeder::class);

        // Utilizador fixo para testes manuais de login rápido.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'tipo' => 'Candidato',
        ]);

        if (app()->environment('local')) {
            $this->seedDadosDemo();
        }
    }

    /**
     * Popula a base de dados local com um conjunto de dados de demonstração:
     * empresas com vagas, candidatos com perfis e habilidades, e candidaturas.
     */
    private function seedDadosDemo(): void
    {
        $skills = Skill::all();

        // 5 empresas, cada uma com 2 a 4 vagas.
        User::factory()
            ->empresa()
            ->count(5)
            ->create()
            ->each(function (User $empresa) {
                Vaga::factory()
                    ->count(fake()->numberBetween(2, 4))
                    ->create(['user_id' => $empresa->id]);
            });

        // 20 candidatos, cada um com 2 a 6 habilidades e possivelmente currículo.
        User::factory()
            ->candidato()
            ->count(20)
            ->create()
            ->each(function (User $candidato) use ($skills) {
                $candidato->skills()->attach(
                    $skills->random(fake()->numberBetween(2, 6))->pluck('id')
                );
            });

        // Algumas candidaturas aleatórias, evitando duplicados.
        $candidatos = User::where('tipo', 'Candidato')->get();
        $vagas = Vaga::all();

        foreach ($candidatos as $candidato) {
            foreach ($vagas->random(min(3, $vagas->count())) as $vaga) {
                Candidatura::firstOrCreate([
                    'user_id' => $candidato->id,
                    'vaga_id' => $vaga->id,
                ], [
                    'status' => fake()->randomElement(Candidatura::statusValidos()),
                ]);
            }
        }
    }
}
