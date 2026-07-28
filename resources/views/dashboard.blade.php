@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="max-w-3xl mx-auto">

        <h1 class="text-2xl font-bold mb-2">{{ auth()->user()->name }}</h1>
        <p class="text-gray-500 text-sm mb-8">
            Conta do tipo: <span class="font-medium text-indigo-600">{{ auth()->user()->tipo }}</span>
        </p>


        @if(auth()->user()->isEmpresa())
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Vagas publicadas</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">
                        {{ auth()->user()->vagas()->count() }}
                    </p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total de candidaturas recebidas</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">
                        {{ auth()->user()->vagas()->withCount('candidaturas')->get()->sum('candidaturas_count') }}
                    </p>
                </div>
            </div>

            <h2 class="text-lg font-semibold mb-3">As suas vagas</h2>
            @forelse(auth()->user()->vagas()->withCount('candidaturas')->latest()->get() as $vaga)
                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-3 flex items-center justify-between shadow-sm">
                    <div>
                        <a href="{{ route('vagas.show', $vaga) }}"
                            class="font-medium text-indigo-700 hover:underline">{{ $vaga->titulo }}</a>
                        <p class="text-xs text-gray-400 mt-0.5">
                            {{ $vaga->localizacao }} · {{ $vaga->candidaturas_count }} candidatura(s)
                        </p>
                    </div>
                    <a href="{{ route('vagas.show', $vaga) }}" class="text-sm text-gray-500 hover:text-indigo-600">Ver →</a>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Ainda não publicou nenhuma vaga.</p>
            @endforelse

            <div class="mt-6">
                <a href="{{ route('vagas.create') }}"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-md text-sm hover:bg-indigo-700">
                    Publicar Nova Vaga
                </a>
            </div>


        @elseif(auth()->user()->isCandidato())
            @php
                $candidaturas = auth()->user()->candidaturas()->with('vaga')->latest()->get();
                $aceites = $candidaturas->where('status', 'aceite')->count();
                $pendentes = $candidaturas->where('status', 'pendente')->count();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-8">
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Candidaturas enviadas</p>
                    <p class="text-3xl font-bold text-indigo-600 mt-1">{{ $candidaturas->count() }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Pendentes</p>
                    <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $pendentes }}</p>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Aceites</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $aceites }}</p>
                </div>
            </div>

            <h2 class="text-lg font-semibold mb-3">Candidaturas recentes</h2>
            @forelse($candidaturas->take(5) as $candidatura)
                <div class="bg-white border border-gray-200 rounded-lg p-4 mb-3 flex items-center justify-between shadow-sm">
                    <div>
                        <a href="{{ route('vagas.show', $candidatura->vaga) }}" class="font-medium text-indigo-700 hover:underline">
                            {{ $candidatura->vaga->titulo }}
                        </a>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $candidatura->vaga->localizacao }}</p>
                    </div>
                    <span
                        class="text-xs px-2 py-1 rounded-full
                                                                                            @if($candidatura->status === 'aceite') bg-green-100 text-green-700
                                                                                            @elseif($candidatura->status === 'rejeitado') bg-red-100 text-red-600
                                                                                            @else bg-yellow-100 text-yellow-700 @endif">
                        {{ ucfirst($candidatura->status) }}
                    </span>
                </div>
            @empty
                <p class="text-gray-400 text-sm">Ainda não se candidatou a nenhuma vaga.</p>
            @endforelse

            <div class="mt-6 flex gap-3">
                <a href="{{ route('vagas.index') }}"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-md text-sm hover:bg-indigo-700">
                    Ver todas as vagas
                </a>
                <a href="{{ route('candidaturas.minhas') }}"
                    class="border border-gray-300 text-gray-600 px-5 py-2 rounded-md text-sm hover:bg-gray-50">
                    Todas as candidaturas
                </a>
            </div>
        @endif

    </div>
@endsection