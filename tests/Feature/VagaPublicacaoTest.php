<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VagaPublicacaoTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Regressão: /vagas/create tem de abrir o formulário de publicação,
     * nunca ser interpretada como /vagas/{vaga} com vaga="create".
     */
    public function test_empresa_consegue_aceder_ao_formulario_de_publicar_vaga(): void
    {
        $empresa = User::factory()->empresa()->create();

        $response = $this->actingAs($empresa)->get(route('vagas.create'));

        $response->assertOk();
        $response->assertViewIs('vagas.create');
        $response->assertSee('Publicar Nova Vaga');
    }

    public function test_candidato_nao_pode_aceder_ao_formulario_de_publicar_vaga(): void
    {
        $candidato = User::factory()->candidato()->create();

        $response = $this->actingAs($candidato)->get(route('vagas.create'));

        $response->assertForbidden();
    }

    public function test_visitante_nao_pode_aceder_ao_formulario_de_publicar_vaga(): void
    {
        $response = $this->get(route('vagas.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_empresa_consegue_publicar_uma_vaga(): void
    {
        $empresa = User::factory()->empresa()->create();

        $response = $this->actingAs($empresa)->post(route('vagas.store'), [
            'titulo' => 'Programador Laravel',
            'descricao' => 'Vaga para desenvolvedor Laravel com experiência em APIs REST.',
            'localizacao' => 'Luanda',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('vagas', [
            'user_id' => $empresa->id,
            'titulo' => 'Programador Laravel',
        ]);
    }

    /**
     * Regressão: uma vaga cujo id textual coincidiria com outras rotas
     * literais (ex: 'create') não existe nunca na prática (id é numérico
     * autoincremental), mas confirmamos aqui que vagas normais continuam
     * a abrir corretamente depois da correção da ordem das rotas.
     */
    public function test_pagina_de_detalhe_da_vaga_continua_acessivel(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);

        $response = $this->get(route('vagas.show', $vaga));

        $response->assertOk();
        $response->assertSee($vaga->titulo);
    }
}
