<?php

use App\Http\Controllers\VagaController;
use App\Http\Controllers\CandidaturaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('vagas.index');
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('vagas', VagaController::class);

    Route::post('/vagas/{vaga}/candidatar', [CandidaturaController::class, 'store'])
        ->name('candidaturas.store');

    Route::patch('/candidaturas/{candidatura}', [CandidaturaController::class, 'atualizar'])
        ->name('candidaturas.atualizar');

    Route::get('/minhas-candidaturas', [CandidaturaController::class, 'minhas'])
        ->name('candidaturas.minhas');
});

require __DIR__ . '/auth.php';