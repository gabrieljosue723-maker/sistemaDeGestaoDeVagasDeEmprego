<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'tipo' => 'Candidato',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_new_empresa_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Empresa Teste',
            'email' => 'empresa@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'tipo' => 'Empresa',
        ]);

        $this->assertAuthenticated();
        $this->assertTrue(auth()->user()->isEmpresa());
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_registration_requires_a_valid_tipo(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'tipo' => 'Administrador',
        ]);

        $response->assertSessionHasErrors('tipo');
        $this->assertGuest();
    }
}
