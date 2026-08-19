<?php

namespace Tests\Feature;

use App\Models\Skill;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesquisaTest extends TestCase
{
    use RefreshDatabase;

    public function test_pesquisa_de_vagas_encontra_por_titulo(): void
    {
        $empresa = User::factory()->empresa()->create();
        Vaga::factory()->create(['user_id' => $empresa->id, 'titulo' => 'Programador Laravel Sénior']);
        Vaga::factory()->create(['user_id' => $empresa->id, 'titulo' => 'Assistente Administrativo']);

        $response = $this->get(route('vagas.index', ['busca' => 'Laravel']));

        $response->assertOk();
        $response->assertSee('Programador Laravel Sénior');
        $response->assertDontSee('Assistente Administrativo');
    }

    public function test_pesquisa_de_vagas_encontra_por_nome_da_empresa(): void
    {
        $empresa = User::factory()->empresa()->create(['name' => 'Acme Soluções Digitais']);
        Vaga::factory()->create(['user_id' => $empresa->id, 'titulo' => 'Vaga Genérica']);

        $response = $this->get(route('vagas.index', ['busca' => 'Acme']));

        $response->assertOk();
        $response->assertSee('Vaga Genérica');
    }

    public function test_empresa_pode_pesquisar_candidatos_por_experiencia_minima(): void
    {
        $empresa = User::factory()->empresa()->create();
        User::factory()->candidato()->create(['name' => 'Candidato Júnior', 'anos_experiencia' => 1]);
        User::factory()->candidato()->create(['name' => 'Candidato Sénior', 'anos_experiencia' => 10]);

        $response = $this->actingAs($empresa)
            ->get(route('candidatos.index', ['experiencia_min' => 5]));

        $response->assertOk();
        $response->assertSee('Candidato Sénior');
        $response->assertDontSee('Candidato Júnior');
    }

    public function test_empresa_pode_pesquisar_candidatos_por_habilidade(): void
    {
        $empresa = User::factory()->empresa()->create();
        $php = Skill::create(['nome' => 'PHP']);
        $design = Skill::create(['nome' => 'Design UI/UX']);

        $devPhp = User::factory()->candidato()->create(['name' => 'Dev PHP']);
        $devPhp->skills()->attach($php);

        $designer = User::factory()->candidato()->create(['name' => 'Designer Criativo']);
        $designer->skills()->attach($design);

        $response = $this->actingAs($empresa)
            ->get(route('candidatos.index', ['skills' => [$php->id]]));

        $response->assertOk();
        $response->assertSee('Dev PHP');
        $response->assertDontSee('Designer Criativo');
    }

    public function test_candidato_nao_pode_aceder_a_pesquisa_de_candidatos(): void
    {
        $candidato = User::factory()->candidato()->create();

        $response = $this->actingAs($candidato)->get(route('candidatos.index'));

        $response->assertForbidden();
    }
}
