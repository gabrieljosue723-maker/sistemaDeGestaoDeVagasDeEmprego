<?php

use App\Http\Controllers\CandidatoController;
use App\Http\Controllers\CandidaturaController;
use App\Http\Controllers\CurriculoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VagaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('vagas.index');
});

// Pesquisa de vagas: pública, para qualquer visitante.
Route::get('/vagas', [VagaController::class, 'index'])->name('vagas.index');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/perfil/foto', [ProfileController::class, 'destroyFoto'])->name('foto.destroy');

    // IMPORTANTE: /vagas/create e /vagas/{vaga}/edit têm de ser registadas
    // ANTES de /vagas/{vaga} (mais abaixo), senão o Laravel interpreta
    // "create" como se fosse o {vaga} da rota de detalhe (curinga genérico
    // apanha primeiro por estar registada antes na versão anterior).
    Route::get('/vagas/create', [VagaController::class, 'create'])->name('vagas.create');
    Route::post('/vagas', [VagaController::class, 'store'])->name('vagas.store');
    Route::get('/vagas/{vaga}/edit', [VagaController::class, 'edit'])->name('vagas.edit');
    Route::put('/vagas/{vaga}', [VagaController::class, 'update']);
    Route::patch('/vagas/{vaga}', [VagaController::class, 'update'])->name('vagas.update');
    Route::delete('/vagas/{vaga}', [VagaController::class, 'destroy'])->name('vagas.destroy');

    Route::post('/vagas/{vaga}/candidatar', [CandidaturaController::class, 'store'])
        ->name('candidaturas.store');

    Route::patch('/candidaturas/{candidatura}', [CandidaturaController::class, 'atualizar'])
        ->name('candidaturas.atualizar');

    Route::get('/minhas-candidaturas', [CandidaturaController::class, 'minhas'])
        ->name('candidaturas.minhas');

    // Currículo em PDF dos candidatos
    Route::get('/candidatos/{candidato}/curriculo', [CurriculoController::class, 'download'])
        ->name('curriculo.download');
    Route::delete('/perfil/curriculo', [CurriculoController::class, 'destroy'])
        ->name('curriculo.destroy');

    // Pesquisa de candidatos (empresa)
    Route::get('/candidatos', [CandidatoController::class, 'index'])
        ->name('candidatos.index');
    Route::get('/candidatos/{candidato}', [CandidatoController::class, 'show'])
        ->name('candidatos.show');
});

// Detalhe da vaga: rota curinga pública, registada por último de propósito
// (ver nota acima sobre /vagas/create).
Route::get('/vagas/{vaga}', [VagaController::class, 'show'])->name('vagas.show');

require __DIR__.'/auth.php';
