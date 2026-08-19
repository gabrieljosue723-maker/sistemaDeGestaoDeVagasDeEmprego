<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A raiz do site redireciona para a listagem pública de vagas.
     */
    public function test_the_application_redirects_to_vagas(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('vagas.index'));
    }

    public function test_vagas_page_loads_successfully(): void
    {
        $response = $this->get(route('vagas.index'));

        $response->assertStatus(200);
    }
}
