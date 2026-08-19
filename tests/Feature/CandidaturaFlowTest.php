<?php

namespace Tests\Feature;

use App\Models\Candidatura;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidaturaFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidato_pode_candidatar_se_a_uma_vaga(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $candidato = User::factory()->candidato()->create();

        $response = $this->actingAs($candidato)
            ->post(route('candidaturas.store', $vaga));

        $response->assertRedirect();
        $this->assertDatabaseHas('candidaturas', [
            'user_id' => $candidato->id,
            'vaga_id' => $vaga->id,
            'status' => 'pendente',
        ]);
    }

    public function test_candidato_nao_pode_candidatar_se_duas_vezes_a_mesma_vaga(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $candidato = User::factory()->candidato()->create();

        $this->actingAs($candidato)->post(route('candidaturas.store', $vaga));
        $this->actingAs($candidato)->post(route('candidaturas.store', $vaga));

        $this->assertSame(1, Candidatura::where('user_id', $candidato->id)
            ->where('vaga_id', $vaga->id)
            ->count());
    }

    public function test_empresa_nao_pode_candidatar_se_a_uma_vaga(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $outraEmpresa = User::factory()->empresa()->create();

        $response = $this->actingAs($outraEmpresa)
            ->post(route('candidaturas.store', $vaga));

        $response->assertForbidden();
    }

    public function test_empresa_dona_da_vaga_pode_aceitar_uma_candidatura(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $candidato = User::factory()->candidato()->create();
        $candidatura = Candidatura::factory()->create([
            'user_id' => $candidato->id,
            'vaga_id' => $vaga->id,
            'status' => 'pendente',
        ]);

        $response = $this->actingAs($empresa)
            ->patch(route('candidaturas.atualizar', $candidatura), ['status' => 'aceite']);

        $response->assertRedirect();
        $this->assertSame('aceite', $candidatura->fresh()->status);
    }

    public function test_empresa_dona_da_vaga_pode_recusar_uma_candidatura(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $candidatura = Candidatura::factory()->create(['vaga_id' => $vaga->id, 'status' => 'pendente']);

        $this->actingAs($empresa)
            ->patch(route('candidaturas.atualizar', $candidatura), ['status' => 'rejeitado']);

        $this->assertSame('rejeitado', $candidatura->fresh()->status);
    }

    public function test_empresa_dona_da_vaga_pode_deixar_pendente(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $candidatura = Candidatura::factory()->create(['vaga_id' => $vaga->id, 'status' => 'aceite']);

        $this->actingAs($empresa)
            ->patch(route('candidaturas.atualizar', $candidatura), ['status' => 'pendente']);

        $this->assertSame('pendente', $candidatura->fresh()->status);
    }

    public function test_outra_empresa_nao_pode_alterar_candidatura_alheia(): void
    {
        $empresa = User::factory()->empresa()->create();
        $outraEmpresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $candidatura = Candidatura::factory()->create(['vaga_id' => $vaga->id, 'status' => 'pendente']);

        $response = $this->actingAs($outraEmpresa)
            ->patch(route('candidaturas.atualizar', $candidatura), ['status' => 'aceite']);

        $response->assertForbidden();
        $this->assertSame('pendente', $candidatura->fresh()->status);
    }

    public function test_candidato_ve_as_suas_candidaturas(): void
    {
        $candidato = User::factory()->candidato()->create();
        Candidatura::factory()->count(3)->create(['user_id' => $candidato->id]);

        $response = $this->actingAs($candidato)->get(route('candidaturas.minhas'));

        $response->assertOk();
    }
}
