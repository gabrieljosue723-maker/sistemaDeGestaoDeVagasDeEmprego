@extends('layouts.app')
@section('title', 'Vagas Disponíveis')

@section('content')

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-1">Vagas Disponíveis</h1>
        <p class="text-sm text-gray-500 mb-5">Vagas publicadas pela sua empresa para gestão e candidaturas</p>

        <div class="flex flex-col sm:flex-row gap-3">
            <form method="GET" action="{{ route('vagas.index') }}" class="flex gap-2 flex-1">
                <input type="text" name="busca" value="{{ request('busca') }}"
                    placeholder="Pesquisar por título ou localização..."
                    class="flex-1 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:border-cyan-400 bg-white">
                <button type="submit" class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold"
                    style="background:#0891b2">
                    Pesquisar
                </button>
            </form>

            @auth
                @if(auth()->user()->isEmpresa())
                    <a href="{{ route('vagas.create') }}"
                        class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-800 shrink-0" style="background:#facc15">
                        Publicar Vaga
                    </a>
                @endif
            @endauth
        </div>

        @if(request('busca'))
            <div class="mt-3 flex items-center gap-2 text-sm text-gray-500">
                <span>Resultados para: <strong class="text-gray-700">{{ request('busca') }}</strong></span>
                <a href="{{ route('vagas.index') }}" class="text-cyan-600 hover:underline">limpar</a>
            </div>
        @endif
    </div>

    @forelse($vagas as $vaga)
        <div class="bg-white border border-gray-100 rounded-2xl p-6 mb-4 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-bold text-gray-900 mb-0.5">
                        <a href="{{ route('vagas.show', $vaga) }}" class="hover:text-cyan-700 transition-colors">
                            {{ $vaga->titulo }}
                        </a>
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $vaga->empresa->name }}
                        <span class="mx-1 text-gray-300">&middot;</span>
                        {{ $vaga->localizacao }}
                    </p>
                    <p class="text-gray-600 mt-3 text-sm leading-relaxed line-clamp-2">{{ $vaga->descricao }}</p>
                </div>
                <span class="shrink-0 text-xs text-gray-400 whitespace-nowrap pt-0.5">
                    {{ $vaga->created_at->diffForHumans() }}
                </span>
            </div>

            <div class="mt-5 pt-4 border-t border-gray-50 flex items-center gap-4">
                <a href="{{ route('vagas.show', $vaga) }}" class="text-sm font-semibold transition-colors"
                    style="color:#0891b2">
                    Ver detalhes
                </a>

                @auth
                    @if(auth()->user()->isEmpresa() && auth()->id() === $vaga->user_id)
                        <span class="text-gray-200">|</span>
                        <a href="{{ route('vagas.edit', $vaga) }}" class="text-sm font-medium transition-colors" style="color:#ca8a04">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('vagas.destroy', $vaga) }}"
                            onsubmit="return confirm('Remover esta vaga?')">
                            @csrf @method('DELETE')
                            <button class="text-sm font-medium text-red-400 hover:text-red-600 transition-colors">Remover</button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="bg-white border border-dashed border-gray-200 rounded-2xl px-6 py-20 text-center">
            <p class="text-lg font-semibold text-gray-700 mb-1">Nenhuma vaga encontrada</p>
            <p class="text-sm text-gray-400 mb-5">Tente ajustar os termos de pesquisa</p>
            @auth
                @if(auth()->user()->isEmpresa())
                    <a href="{{ route('vagas.create') }}" class="text-sm font-semibold px-5 py-2 rounded-xl text-white"
                        style="background:#0891b2">
                        Publicar vaga
                    </a>
                @endif
            @endauth
        </div>
    @endforelse

    <div class="mt-6">
        {{ $vagas->withQueryString()->links() }}
    </div>

@endsection