<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Lista de habilidades comuns disponíveis para os candidatos selecionarem no perfil.
     */
    public function run(): void
    {
        $habilidades = [
            'PHP',
            'Laravel',
            'JavaScript',
            'React',
            'Vue.js',
            'SQL',
            'Python',
            'Excel',
            'Gestão de Projetos',
            'Comunicação',
            'Atendimento ao Cliente',
            'Design UI/UX',
            'Marketing Digital',
            'Contabilidade',
            'Recursos Humanos',
            'Inglês',
            'Vendas',
            'Redes e Infraestrutura',
            'Análise de Dados',
            'Liderança de Equipas',
        ];

        foreach ($habilidades as $nome) {
            Skill::firstOrCreate(['nome' => $nome]);
        }
    }
}
