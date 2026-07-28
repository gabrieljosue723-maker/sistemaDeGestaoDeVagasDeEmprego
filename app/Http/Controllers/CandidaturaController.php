<?php

namespace App\Http\Controllers;

use App\Models\Vaga;
use App\Models\Candidatura;
use Illuminate\Http\Request;

class CandidaturaController extends Controller
{
    public function store(Vaga $vaga)
    {
        $this->authorize('candidatar', $vaga);

        Candidatura::firstOrCreate([
            'user_id' => auth()->id(),
            'vaga_id' => $vaga->id,
        ]);

        return back()->with('sucesso', 'Candidatura enviada com sucesso!');
    }

    public function atualizar(Request $request, Candidatura $candidatura)
    {
        $this->authorize('atualizar', $candidatura);

        $request->validate([
            'status' => ['required', 'in:' . implode(',', Candidatura::statusValidos())],
        ]);

        $candidatura->update(['status' => $request->status]);

        $mensagem = match ($request->status) {
            'aceite' => 'Candidatura aceite.',
            'rejeitado' => 'Candidatura rejeitada.',
            default => 'Status atualizado.',
        };

        return back()->with('sucesso', $mensagem);
    }

    public function minhas()
    {
        $candidaturas = auth()->user()
            ->candidaturas()
            ->with('vaga.empresa')
            ->latest()
            ->get();

        return view('candidaturas.minhas', compact('candidaturas'));
    }
}