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

// Pesquisa e visualização de vagas: públicas, para qualquer visitante.
Route::resource('vagas', VagaController::class)->only(['index', 'show']);

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('vagas', VagaController::class)->except(['index', 'show']);

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

require __DIR__.'/auth.php';
