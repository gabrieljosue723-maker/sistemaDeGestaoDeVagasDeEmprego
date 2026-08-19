<?php

namespace App\Http\Controllers;

use App\Http\Requests\VagaRequest;
use App\Models\Vaga;
use Illuminate\Http\Request;

class VagaController extends Controller
{
    public function index(Request $request)
    {
        $vagas = Vaga::with('empresa')
            ->when($request->busca, fn ($q) => $q->busca($request->busca))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('vagas.index', compact('vagas'));
    }

    public function create()
    {
        $this->authorize('create', Vaga::class);

        return view('vagas.create');
    }

    public function store(VagaRequest $request)
    {
        $this->authorize('create', Vaga::class);

        Vaga::create([
            'user_id' => auth()->id(),
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'localizacao' => $request->localizacao,
        ]);

        return redirect()->route('vagas.index')
            ->with('sucesso', 'Vaga publicada com sucesso!');
    }

    public function show(Vaga $vaga)
    {
        $vaga->load('empresa', 'candidaturas.candidato');

        $jaCandidatou = false;
        if (auth()->check() && auth()->user()->isCandidato()) {
            $jaCandidatou = $vaga->candidaturas
                ->where('user_id', auth()->id())
                ->isNotEmpty();
        }

        return view('vagas.show', compact('vaga', 'jaCandidatou'));
    }

    public function edit(Vaga $vaga)
    {
        $this->authorize('update', $vaga);

        return view('vagas.edit', compact('vaga'));
    }

    public function update(VagaRequest $request, Vaga $vaga)
    {
        $this->authorize('update', $vaga);

        $vaga->update($request->validated());

        return redirect()->route('vagas.show', $vaga)
            ->with('sucesso', 'Vaga atualizada com sucesso!');
    }

    public function destroy(Vaga $vaga)
    {
        $this->authorize('delete', $vaga);

        $vaga->delete();

        return redirect()->route('vagas.index')
            ->with('sucesso', 'Vaga removida.');
    }
}
