@extends('layouts.app')
@section('title', 'Minhas Candidaturas')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-gray-900">Minhas Candidaturas</h1>

    @forelse($candidaturas as $candidatura)
        <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <a href="{{ route('vagas.show', $candidatura->vaga) }}"
                    class="text-base font-semibold text-gray-900 hover:text-cyan-700 transition-colors">
                    {{ $candidatura->vaga->titulo }}
                </a>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $candidatura->vaga->empresa->name }} &middot; {{ $candidatura->vaga->localizacao }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    Candidatura enviada em {{ $candidatura->created_at->translatedFormat('d/m/Y') }}
                </p>
            </div>

            <span class="shrink-0 mt-1 text-xs px-3 py-1 rounded-full font-semibold
                        @if($candidatura->status === 'aceite') bg-green-100 text-green-700
                        @elseif($candidatura->status === 'rejeitado') bg-red-100 text-red-600
                        @else bg-yellow-100 text-yellow-700 @endif">
                {{ ucfirst($candidatura->status) }}
            </span>
        </div>
    @empty
        <div class="text-center text-gray-500 py-24">
            <p class="text-lg font-medium mb-2">Ainda não enviou nenhuma candidatura.</p>
            <a href="{{ route('vagas.explorar') }}" class="text-cyan-600 hover:underline mt-2 inline-block text-sm">
                Explorar vagas disponíveis
            </a>
        </div>
    @endforelse
@endsection