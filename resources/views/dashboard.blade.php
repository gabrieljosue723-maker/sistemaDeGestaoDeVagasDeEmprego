@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="max-w-3xl mx-auto">

        <div class="flex items-center gap-4 mb-8">
            <x-avatar :user="auth()->user()" size="lg" />
            <div>
                <h1 class="text-2xl font-bold text-neutral-900">{{ auth()->user()->name }}</h1>
                <p class="text-neutral-500 text-sm">
                    Conta do tipo: <span class="font-semibold text-neutral-800">{{ auth()->user()->tipo }}</span>
                </p>
            </div>
        </div>

        @if(auth()->user()->isEmpresa())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                <div class="bg-white border border-neutral-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Vagas publicadas</p>
                    <p class="text-3xl font-bold text-neutral-900 mt-1">
                        {{ auth()->user()->vagas()->count() }}
                    </p>
                </div>
                <div class="bg-white border border-neutral-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Total de candidaturas recebidas</p>
                    <p class="text-3xl font-bold text-neutral-900 mt-1">
                        {{ auth()->user()->vagas()->withCount('candidaturas')->get()->sum('candidaturas_count') }}
                    </p>
                </div>
            </div>

            <h2 class="text-lg font-semibold mb-3 text-neutral-900">As suas vagas</h2>
            @forelse(auth()->user()->vagas()->withCount('candidaturas')->latest()->get() as $vaga)
                <div class="bg-white border border-neutral-200 rounded-lg p-4 mb-3 flex items-center justify-between shadow-sm">
                    <div>
                        <a href="{{ route('vagas.show', $vaga) }}"
                            class="font-medium text-neutral-900 hover:underline">{{ $vaga->titulo }}</a>
                        <p class="text-xs text-neutral-400 mt-0.5">
                            {{ $vaga->localizacao }} &middot; {{ $vaga->candidaturas_count }} candidatura(s)
                        </p>
                    </div>
                    <a href="{{ route('vagas.show', $vaga) }}" class="text-sm text-neutral-500 hover:text-neutral-900">Ver &rarr;</a>
                </div>
            @empty
                <p class="text-neutral-400 text-sm">Ainda não publicou nenhuma vaga.</p>
            @endforelse

            <div class="mt-6 flex gap-3">
                <x-button-link :href="route('vagas.create')" variant="dark">
                    Publicar Nova Vaga
                </x-button-link>
                <x-button-link :href="route('candidatos.index')" variant="light">
                    Pesquisar Candidatos
                </x-button-link>
            </div>

        @elseif(auth()->user()->isCandidato())
            @php
                $candidaturas = auth()->user()->candidaturas()->with('vaga')->latest()->get();
                $aceites = $candidaturas->where('status', 'aceite')->count();
                $pendentes = $candidaturas->where('status', 'pendente')->count();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                <div class="bg-white border border-neutral-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Candidaturas enviadas</p>
                    <p class="text-3xl font-bold text-neutral-900 mt-1">{{ $candidaturas->count() }}</p>
                </div>
                <div class="bg-white border border-neutral-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Pendentes</p>
                    <p class="text-3xl font-bold text-neutral-500 mt-1">{{ $pendentes }}</p>
                </div>
                <div class="bg-white border border-neutral-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Aceites</p>
                    <p class="text-3xl font-bold text-neutral-900 mt-1">{{ $aceites }}</p>
                </div>
            </div>

            @unless(auth()->user()->temCurriculo())
                <div class="bg-neutral-900 text-white rounded-lg px-5 py-4 mb-6 flex items-center justify-between gap-4">
                    <p class="text-sm">Ainda não enviou o seu currículo em PDF. Empresas não conseguem consultá-lo.</p>
                    <x-button-link :href="route('profile.edit')" variant="light" class="shrink-0">
                        Adicionar currículo
                    </x-button-link>
                </div>
            @endunless

            <h2 class="text-lg font-semibold mb-3 text-neutral-900">Candidaturas recentes</h2>
            @forelse($candidaturas->take(5) as $candidatura)
                <div class="bg-white border border-neutral-200 rounded-lg p-4 mb-3 flex items-center justify-between shadow-sm">
                    <div>
                        <a href="{{ route('vagas.show', $candidatura->vaga) }}" class="font-medium text-neutral-900 hover:underline">
                            {{ $candidatura->vaga->titulo }}
                        </a>
                        <p class="text-xs text-neutral-400 mt-0.5">{{ $candidatura->vaga->localizacao }}</p>
                    </div>
                    <x-status-badge :status="$candidatura->status" />
                </div>
            @empty
                <p class="text-neutral-400 text-sm">Ainda não se candidatou a nenhuma vaga.</p>
            @endforelse

            <div class="mt-6 flex gap-3">
                <x-button-link :href="route('vagas.index')" variant="dark">
                    Ver todas as vagas
                </x-button-link>
                <x-button-link :href="route('candidaturas.minhas')" variant="light">
                    Todas as candidaturas
                </x-button-link>
            </div>
        @endif

    </div>
@endsection
