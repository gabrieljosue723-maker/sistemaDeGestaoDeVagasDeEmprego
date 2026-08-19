@extends('layouts.app')
@section('title', 'Minhas Candidaturas')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-neutral-900">Minhas Candidaturas</h1>

    @forelse($candidaturas as $candidatura)
        <div class="bg-white border border-neutral-200 rounded-xl p-5 mb-4 shadow-sm flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <a href="{{ route('vagas.show', $candidatura->vaga) }}"
                    class="text-base font-semibold text-neutral-900 hover:underline transition-colors">
                    {{ $candidatura->vaga->titulo }}
                </a>
                <p class="text-sm text-neutral-500 mt-0.5">
                    {{ $candidatura->vaga->empresa->name }} &middot; {{ $candidatura->vaga->localizacao }}
                </p>
                <p class="text-xs text-neutral-400 mt-1">
                    Candidatura enviada em {{ $candidatura->created_at->translatedFormat('d/m/Y') }}
                </p>
            </div>

            <x-status-badge :status="$candidatura->status" class="mt-1" />
        </div>
    @empty
        <div class="text-center text-neutral-500 py-24">
            <p class="text-lg font-medium mb-2">Ainda não enviou nenhuma candidatura.</p>
            <a href="{{ route('vagas.index') }}" class="btn-ripple text-neutral-900 underline underline-offset-2 hover:text-neutral-600 mt-2 inline-block text-sm">
                Explorar vagas disponíveis
            </a>
        </div>
    @endforelse
@endsection
