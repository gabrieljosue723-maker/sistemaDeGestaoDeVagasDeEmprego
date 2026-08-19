<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class CandidatoController extends Controller
{
    /**
     * Lista/pesquisa candidatos com filtros combináveis:
     * texto livre, anos de experiência mínimos, habilidades,
     * localização, formação e disponibilidade.
     */
    public function index(Request $request)
    {
        $this->authorize('pesquisarCandidatos', User::class);

        $filtros = $request->only([
            'texto', 'experiencia_min', 'localizacao', 'formacao', 'disponibilidade',
        ]);
        $filtros['skills'] = $request->input('skills', []);

        $candidatos = User::filtrarCandidatos($filtros)
            ->with('skills')
            ->withCount('candidaturas')
            ->orderByDesc('anos_experiencia')
            ->paginate(9)
            ->withQueryString();

        $skills = Skill::orderBy('nome')->get();

        return view('candidatos.index', compact('candidatos', 'skills', 'filtros'));
    }

    /**
     * Mostra o perfil detalhado de um candidato para a empresa.
     */
    public function show(User $candidato)
    {
        $this->authorize('verPerfil', $candidato);

        $candidato->load('skills');

        $podeVerCurriculo = auth()->user()->can('verCurriculo', $candidato);

        return view('candidatos.show', compact('candidato', 'podeVerCurriculo'));
    }
}
