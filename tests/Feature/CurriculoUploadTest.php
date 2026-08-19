<?php

namespace Tests\Feature;

use App\Models\Candidatura;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CurriculoUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_candidato_pode_enviar_o_curriculo_em_pdf(): void
    {
        $candidato = User::factory()->candidato()->create();
        $ficheiro = UploadedFile::fake()->create('curriculo.pdf', 200, 'application/pdf');

        $response = $this->actingAs($candidato)->patch(route('profile.update'), [
            'name' => $candidato->name,
            'email' => $candidato->email,
            'curriculo' => $ficheiro,
        ]);

        $response->assertSessionHasNoErrors();
        $candidato->refresh();

        $this->assertTrue($candidato->temCurriculo());
        Storage::disk('public')->assertExists($candidato->curriculo_path);
    }

    public function test_upload_rejeita_ficheiros_que_nao_sejam_pdf(): void
    {
        $candidato = User::factory()->candidato()->create();
        $ficheiro = UploadedFile::fake()->create('curriculo.docx', 200, 'application/msword');

        $response = $this->actingAs($candidato)->patch(route('profile.update'), [
            'name' => $candidato->name,
            'email' => $candidato->email,
            'curriculo' => $ficheiro,
        ]);

        $response->assertSessionHasErrors('curriculo');
    }

    public function test_o_proprio_candidato_pode_descarregar_o_seu_curriculo(): void
    {
        $candidato = User::factory()->candidato()->create([
            'curriculo_path' => 'curriculos/exemplo.pdf',
            'curriculo_nome_original' => 'exemplo.pdf',
        ]);
        Storage::disk('public')->put('curriculos/exemplo.pdf', 'conteudo-fake');

        $response = $this->actingAs($candidato)->get(route('curriculo.download', $candidato));

        $response->assertOk();
    }

    public function test_empresa_sem_candidatura_nao_pode_descarregar_curriculo(): void
    {
        $candidato = User::factory()->candidato()->create([
            'curriculo_path' => 'curriculos/exemplo.pdf',
            'curriculo_nome_original' => 'exemplo.pdf',
        ]);
        Storage::disk('public')->put('curriculos/exemplo.pdf', 'conteudo-fake');
        $empresa = User::factory()->empresa()->create();

        $response = $this->actingAs($empresa)->get(route('curriculo.download', $candidato));

        $response->assertForbidden();
    }

    public function test_empresa_com_candidatura_recebida_pode_descarregar_curriculo(): void
    {
        $empresa = User::factory()->empresa()->create();
        $vaga = Vaga::factory()->create(['user_id' => $empresa->id]);
        $candidato = User::factory()->candidato()->create([
            'curriculo_path' => 'curriculos/exemplo.pdf',
            'curriculo_nome_original' => 'exemplo.pdf',
        ]);
        Storage::disk('public')->put('curriculos/exemplo.pdf', 'conteudo-fake');

        Candidatura::factory()->create([
            'user_id' => $candidato->id,
            'vaga_id' => $vaga->id,
        ]);

        $response = $this->actingAs($empresa)->get(route('curriculo.download', $candidato));

        $response->assertOk();
    }
}
