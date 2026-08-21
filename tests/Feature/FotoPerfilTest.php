<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FotoPerfilTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_candidato_pode_enviar_foto_de_perfil(): void
    {
        $candidato = User::factory()->candidato()->create();
        $foto = UploadedFile::fake()->image('foto.jpg', 300, 300);

        $response = $this->actingAs($candidato)->patch(route('profile.update'), [
            'name' => $candidato->name,
            'email' => $candidato->email,
            'foto' => $foto,
        ]);

        $response->assertSessionHasNoErrors();
        $candidato->refresh();

        $this->assertTrue($candidato->temFoto());
        Storage::disk('public')->assertExists($candidato->foto_path);
    }

    public function test_empresa_pode_enviar_logotipo(): void
    {
        $empresa = User::factory()->empresa()->create();
        $logo = UploadedFile::fake()->image('logo.png', 200, 200);

        $response = $this->actingAs($empresa)->patch(route('profile.update'), [
            'name' => $empresa->name,
            'email' => $empresa->email,
            'foto' => $logo,
        ]);

        $response->assertSessionHasNoErrors();
        $empresa->refresh();

        $this->assertTrue($empresa->temFoto());
        Storage::disk('public')->assertExists($empresa->foto_path);
    }

    public function test_upload_de_foto_rejeita_ficheiros_que_nao_sejam_imagem(): void
    {
        $candidato = User::factory()->candidato()->create();
        $ficheiro = UploadedFile::fake()->create('documento.pdf', 100, 'application/pdf');

        $response = $this->actingAs($candidato)->patch(route('profile.update'), [
            'name' => $candidato->name,
            'email' => $candidato->email,
            'foto' => $ficheiro,
        ]);

        $response->assertSessionHasErrors('foto');
    }

    public function test_nova_foto_substitui_e_remove_a_anterior(): void
    {
        $candidato = User::factory()->candidato()->create();
        Storage::disk('public')->put('fotos/antiga.jpg', 'conteudo-antigo');
        $candidato->update(['foto_path' => 'fotos/antiga.jpg']);

        $novaFoto = UploadedFile::fake()->image('nova.jpg');

        $this->actingAs($candidato)->patch(route('profile.update'), [
            'name' => $candidato->name,
            'email' => $candidato->email,
            'foto' => $novaFoto,
        ]);

        Storage::disk('public')->assertMissing('fotos/antiga.jpg');
        Storage::disk('public')->assertExists($candidato->fresh()->foto_path);
    }

    public function test_utilizador_pode_remover_a_foto_atual(): void
    {
        $candidato = User::factory()->candidato()->create();
        Storage::disk('public')->put('fotos/atual.jpg', 'conteudo');
        $candidato->update(['foto_path' => 'fotos/atual.jpg']);

        $response = $this->actingAs($candidato)->delete(route('foto.destroy'));

        $response->assertRedirect();
        Storage::disk('public')->assertMissing('fotos/atual.jpg');
        $this->assertNull($candidato->fresh()->foto_path);
    }
}
